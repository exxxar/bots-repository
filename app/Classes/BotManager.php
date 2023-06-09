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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class BotManager extends BotCore
{
    use BotMethodsTrait, BotDialogTrait;

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

        $this->botUser = BotUser::query()
            ->where("bot_id", $this->getSelf()->id)
            ->where("telegram_chat_id", $telegram_chat_id)
            ->first();

        if (is_null($this->botUser)) {
            try {
                $uuid = Str::uuid();

                $role = Role::query()
                    ->where("slug", "owner")
                    ->first();

                $user = User::query()->create([
                    'name' => $username ?? $telegram_chat_id ?? "unknown",
                    'email' => "$uuid@your-cashman.ru",
                    'password' => bcrypt($telegram_chat_id),
                    'role_id' => $role->id,
                ]);

                $this->botUser = BotUser::query()->create([
                    'bot_id' => $this->getSelf()->id,
                    'user_id' => $user->id,
                    'is_vip' => false,
                    'is_admin' => false,
                    'is_work' => false,
                    'user_in_location' => false,
                    'telegram_chat_id' => $telegram_chat_id,
                    'fio_from_telegram' => "$first_name $last_name" ?? null,
                ]);

                CashBack::query()->create([
                    'user_id' => $user->id,
                    'bot_id' => $this->getSelf()->id,
                    'amount' => 0,
                ]);
            } catch (\Exception $e) {
                Log::info("Error TBH " . $e->getMessage() . " " . $e->getLine());
            }

        }
    }

    protected function botStatusHandler(): BotStatusEnum
    {
        if ($this->checkIsWorking())
            return BotStatusEnum::Working;


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
    }

    public function getSelf()
    {
        return Bot::query()
            ->withTrashed()
            ->with(["botUsers", "company", "imageMenus", "company.locations"])
            ->where("bot_domain", $this->domain)
            ->first();
    }

    protected function prepareTemplatePage($page)
    {

        if (is_null($page))
            return;

        $bot = $this->getSelf();

        $inlineKeyboard = $page->inlineKeyboard ?? null;
        $replyKeyboard = $page->replyKeyboard ?? null;

        $iMenu = is_null($inlineKeyboard) ? [] : ($inlineKeyboard->menu ?? []);
        $rMenu = is_null($replyKeyboard) ? [] : ($replyKeyboard->menu ?? []);

        $content = str_replace(["<p>", "</p>"], "", $page->content);
        $content = str_replace(["<br>"], "\n", $content);

        //$content = sprintf($content);

        $needSendReplyMenu = true;

        $images = [];
        if (is_array($page->images)) {
            $images = $page->images;
        }


        if (count($images) > 1) {

            $media = [];
            foreach ($images as $image) {

                $media[] = [
                    "media" => env("APP_URL") . "/images-by-bot-id/" . $bot->id . "/" . $image,
                    "type" => "photo",
                    "caption" => "$image"
                ];
            }

            $this->replyMediaGroup($media);
            $this->replyInlineKeyboard($content, $iMenu);

            if (!empty($replyKeyboard))
                $this->replyKeyboard("Меню страницы", $rMenu);

            $needSendReplyMenu = false;

        } else if (count($images) === 1) {


            $this->replyPhoto($content,
                InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $images[0]),
                $iMenu
            );

        } else if (count($images) === 0) {
            $this->replyInlineKeyboard($content, $iMenu);
        }

        if (!empty($replyKeyboard)&&$needSendReplyMenu)
            $this->replyKeyboard("Меню страницы", $rMenu);




    }

}
