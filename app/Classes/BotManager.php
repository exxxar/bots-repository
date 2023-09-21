<?php

namespace App\Classes;

use App\Enums\BotStatusEnum;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class BotManager extends BotCore
{
    use BotMethodsTrait, BotDialogTrait;

    private $recursivePages;

    private $botUser;

    public function bot()
    {
        $this->controller = null;
        return $this;
    }

    public function currentBotUser()
    {
        return $this->botUser;
    }

    protected function checkIsWorking()
    {
        return $this->getSelf()->is_active &&
            $this->getSelf()->balance > 0 &&
            is_null($this->getSelf()->deleted_at);
    }

    protected function createUser($from)
    {
        $telegram_chat_id = $from->id; //идентификатор чата пользователя из телеграм
        $first_name = $from->first_name ?? null; //имя пользователя из телеграм
        $last_name = $from->last_name ?? null; //фамилия пользователя из телеграм
        $username = $from->username ?? null; //псевдоним пользователя

        $anyBotUser = BotUser::query()
            ->where("telegram_chat_id", $telegram_chat_id)
            ->first();

        $existUserId = null;

        if (!is_null($anyBotUser))
            $existUserId = $anyBotUser->user_id;

        $this->botUser = BotUser::query()
            ->where("bot_id", $this->getSelf()->id)
            ->where("telegram_chat_id", $telegram_chat_id)
            ->first();


        if (is_null($this->botUser)) {
            try {
                // $uuid = Str::uuid();

                $role = Role::query()
                    ->where("slug", "user")
                    ->first();

                if (is_null($existUserId))
                    $user = User::query()->create([
                        'name' => $username ?? $telegram_chat_id ?? "unknown",
                        'email' => "$telegram_chat_id@your-cashman.ru",
                        'password' => bcrypt($telegram_chat_id),
                        'role_id' => $role->id,
                    ]);

                $this->botUser = BotUser::query()->create([
                    'bot_id' => $this->getSelf()->id,
                    'user_id' => $existUserId ?? $user->id ?? null,
                    'username' => $username,
                    'is_vip' => false,
                    'is_admin' => false,
                    'is_work' => false,
                    'user_in_location' => false,
                    'telegram_chat_id' => $telegram_chat_id,
                    'fio_from_telegram' => "$first_name $last_name" ?? null,
                ]);


                CashBack::query()->create([
                    'user_id' => $this->botUser->user_id,
                    'bot_id' => $this->getSelf()->id,
                    'amount' => 0,
                ]);
            } catch (\Exception $e) {
                Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
            }

        } else {
            $this->botUser->updated_at = Carbon::now();
            $this->botUser->save();
        }


    }

    protected function botStatusHandler(): BotStatusEnum
    {
        if ($this->checkIsWorking())
            return BotStatusEnum::Working;


        if ($this->botUser->is_admin) {
            $this->reply("<b>Сервер недоступен для пользователей!</b>");
            return BotStatusEnum::Working;
        }


        $message = $this->getSelf()->maintenance_message ?? 'Техническое обслуживание';

        $this
            ->replyPhoto("\xF0\x9F\x9A\xA8В данный момент сервис временно недосутепн! Обратитесь в тех. поддержку:\xF0\x9F\x9A\xA8\n\n<em><b>$message</b></em>",
                InputFile::create(public_path() . "/images/maintenance.png"),
                [
                    [
                        ["text" => "\xF0\x9F\x9A\xA7Написать в тех. поддержку", "url" => "https://t.me/exxxar"]
                    ]
                ]
            );

        return BotStatusEnum::InMaintenance;
    }

    public function setWebhooks()
    {
        $bots = Bot::query()
            ->withTrashed()
            //->where("is_template", false)
            ->get();

        $result = [];
        foreach ($bots as $bot) {
            $botUrl = env("APP_URL") . "/bot/" . $bot->bot_domain;

            $token = env("APP_DEBUG") ?
                ($bot->bot_token_dev ?? null) :
                ($bot->bot_token ?? $bot->bot_token_dev ?? null);

            $telegramUrl = "https://api.telegram.org/bot$token/setWebhook?url=$botUrl";

            $response = Http::get($telegramUrl);

            $token = substr($token, 0, 5) . "*****" . substr($token, strlen($token) - 5, 5);
            $result[] = (object)[
                "id" => $bot->id,
                "bot_domain" => $bot->bot_domain,
                "token" => $token,
                "bot_url" => $botUrl,
                "result" => $response->json()
            ];

        }

        return $result;
    }

    protected function setApiToken($domain)
    {
        try {
            $bot = Bot::query()
                ->withTrashed()
                ->where("bot_domain", $domain)->first();

            if (is_null($bot))
                return;

            $token = env("APP_DEBUG") ?
                ($bot->bot_token_dev ?? null) :
                ($bot->bot_token ?? $bot->bot_token_dev ?? null);

            $this->domain = $domain;

            $this->bot = new Api($token);
        } catch (\Exception $e) {
            $this->bot = null;
            $this->domain = null;
        }

    }

    public function getSelf()
    {
        return Bot::query()
            ->withTrashed()
            ->with([/*"botUsers",*/ "company", "imageMenus", "company.locations"])
            ->where("bot_domain", $this->domain)
            ->first();
    }

    protected function checkTemplatePageRules($page)
    {
        $rules = $page->rules_if;

        $result = false;

        // Log::info("we are here 1".print_r($rules["bot_user"],true));
        if (isset($rules["bot_user"])) {
            $keys = array_keys($rules["bot_user"]);
            // Log::info("we are here 2 (keys)=>".print_r($keys, true));
            foreach ($keys as $key) {
                $botUser = $this->botUser->toArray();

                //Log::info("we are here 3 (foreach and test, botUser)=>".print_r($botUser, true));

                $need = $rules["bot_user"][$key] ?? $botUser[$key];

                $result = ($need === $botUser[$key] && (gettype($botUser[$key]) === "boolean" || gettype($botUser[$key]) === "string")) ||
                    ($need >= $botUser[$key] && gettype($botUser[$key]) === "integer");

                if (!$result)
                    break;
                // Log::info("we are here 4 (result)=>".print_r($result, true));
            }

        }

        if (isset($rules["channels"])) {

            foreach ($rules["channels"] as $channel) {
                try {

                    $data = $this->bot->getChatMember([
                        "chat_id" => $channel,
                        "user_id" => $this->botUser->telegram_chat_id,
                    ]);

                    if ($data["status"] === "left") {
                        $result = false;
                        break;
                    } else
                        $result = true;


                } catch (\Exception $e) {
                    Log::info($e->getMessage() . " " . $e->getLine());
                }

            }

        }

        if ($result && !is_null($page->rules_if_message))
            $this->reply($page->rules_if_message);

        if (!$result && !is_null($page->rules_else_message))
            $this->reply($page->rules_else_message);


        return $result;
    }

    protected function prepareTemplatePage($page)
    {

        $this->recursivePages = ($this->recursivePages ?? []);

        if (!in_array($page->id, $this->recursivePages))
            $this->recursivePages[] = $page->id;
        else
            return;

        if (is_null($page))
            return;

        $bot = $this->getSelf();

        $inlineKeyboard = $page->inlineKeyboard ?? null;
        $replyKeyboard = $page->replyKeyboard ?? null;


        $iMenu = is_null($inlineKeyboard) ? [] : ($inlineKeyboard->menu ?? []);
        $rMenu = is_null($replyKeyboard) ? [] : ($replyKeyboard->menu ?? []);

        $content = str_replace(["<p>", "</p>"], "", $page->content);
        $content = str_replace(["<br>"], "\n", $content);

        $name = $this->botUser->fio_from_telegram ?? $this->botUser->name ?? "Без имени";

        $content = str_replace(["{{userName}}"], $name, $content);

        $telegramChatId = $this->botUser->telegram_chat_id ?? "Не указан";

        $content = str_replace(["{{telegramChatId}}"], $telegramChatId, $content);

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode("001" . $telegramChatId);
        //$content = sprintf($content);
        $qr = "<a href='https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$link'>QR-код</a>";
        $content = str_replace(["{{referralLink}}"], $link, $content);

        $content = str_replace(["{{referralQr}}"], $qr, $content);

        $needContentInReply = true;
        $needSendReplyMenu = true;

        $images = [];
        if (is_array($page->images)) {
            $images = $page->images;
        }

        $replyMenuTitle = $page->reply_keyboard_title ?? null;

        if (count($images) > 1) {

            $media = [];
            foreach ($images as $image) {
                $media[] = [
                    "media" => env("APP_URL") . "/images-by-bot-id/" . $bot->id . "/" . $image,
                    "type" => "photo",
                    "caption" => "$image"
                ];
            }

            try {
                $this->replyMediaGroup($media);
            } catch (\Exception $e) {
                $this->replyPhoto("Ошибочка с изображениями",
                    InputFile::create(public_path() . "/images/cashman2.jpg")
                );
            }


            if (!empty($iMenu)) {
                $this->replyInlineKeyboard($content, $iMenu);
                $needContentInReply = false;
            }


            if (!empty($rMenu)) {
                $this->replyKeyboard($needContentInReply ? $content : ($replyMenuTitle ?? 'Главное меню'), $rMenu);
                $needSendReplyMenu = false;
            }


        }

        if (count($images) === 1) {
            try {
                $this->replyPhoto(mb_strlen($content) < 1024 ? $content : null,
                    InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $images[0]),
                    $iMenu
                );
            } catch (\Exception $e) {
                $this->replyPhoto("Ошибочка с изображением",
                    InputFile::create(public_path() . "/images/cashman2.jpg")
                );
            }

            if (!empty($replyKeyboard))
                $this->replyKeyboard(mb_strlen($content) >= 1024 ? $content : ($replyMenuTitle ?? 'Главное меню'), $rMenu);

            if (empty($replyKeyboard) && mb_strlen($content) >= 1024)
                $this->reply($content);
        }

        if (count($images) === 0) {
            $needContentInReply = empty($iMenu) && is_null($replyMenuTitle);

            if (!$needContentInReply)
                $this->replyInlineKeyboard($content, $iMenu);

            if (!empty($replyKeyboard) && $needSendReplyMenu)
                $this->replyKeyboard($needContentInReply ? $content : ($replyMenuTitle ?? 'Главное меню'), $rMenu);

            if ($needContentInReply && empty($replyKeyboard))
                $this->reply($content);
        }


        if (!is_null($page->next_page_id)) {
            $next = BotPage::query()
                ->find($page->next_page_id);

            $this->prepareTemplatePage($next);
        }


    }

}
