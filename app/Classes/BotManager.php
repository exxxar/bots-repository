<?php

namespace App\Classes;

use App\Enums\BotStatusEnum;
use App\Facades\BusinessLogic;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotExternalRequest;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPUnit\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser = null): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        $this->chatId = $botUser->telegram_chat_id;
        return $this;
    }

    protected function checkIsWorking(): bool
    {
        return ($this->getSelf()->is_active ?? false) &&
            ($this->getSelf()->balance ?? 0) > 0 &&
            is_null($this->getSelf()->deleted_at ?? null);
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
                    $user = User::query()->updateOrCreate([
                        'email' => "$telegram_chat_id@your-cashman.ru",

                    ], [
                        'name' => $username ?? $telegram_chat_id ?? "unknown",
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
                    'bot_user_id' => $this->botUser->id,
                    'bot_id' => $this->getSelf()->id,
                    'amount' => 0,
                ]);


                BusinessLogic::bitrix()
                    ->setBotUser($this->getSelf())
                    ->setBot($this->botUser)
                    ->addLead("Регистрация в боте");

            } catch (\Exception $e) {
                Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
            }

        } else {
            $this->botUser->updated_at = Carbon::now();
            $this->botUser->save();

            $cashback = CashBack::query()
                ->where("user_id", $this->botUser->user_id)
                ->where("bot_id", $this->getSelf()->id)
                ->first();

            if (!is_null($cashback)) {
                $cashback->bot_user_id = $this->botUser->id;
                $cashback->save();
            }
        }


    }

    protected function checkIsUserBlocked(): bool
    {
        if (!is_null($this->botUser->blocked_at ?? null)) {
            $this->reply($this->botUser->blocked_message ??
                "Мы заметили у вас подозрительную активность и временно ограничили доступ. Хорошего дня!");
            return true;
        }
        return false;
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
                        ["text" => "\xF0\x9F\x9A\xA7Написать в тех. поддержку", "url" => "https://t.me/Risha_358"]
                    ]
                ]
            );

        return BotStatusEnum::InMaintenance;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getSlugs()
    {
        return $this->slugs;
    }


    private function prepareServerURL($serverKey = "main")
    {
        if (!file_exists(base_path() . "/servers.json"))
            return null;

        $d = file_get_contents(base_path() . "/servers.json");
        $servers = \Illuminate\Support\Collection::make(json_decode($d));
        $currentServer = $servers->where("key", $serverKey)->first() ?? null;

        if (is_null($currentServer))
            return null;

        return $currentServer->url ?? null;
    }

    public function setWebhooks($botId = null)
    {
        $bots = Bot::query()
            ->withTrashed();

        if (!is_null($botId)) {
            $bots = $bots->where("id", $botId);
        }

        $bots = $bots->get();

        $result = [];
        foreach ($bots as $bot) {

            $serverUrl = $this->prepareServerURL($bot->server ?? null);

            $botUrl = ($serverUrl ?? env("APP_URL")) . "/bot/" . $bot->bot_domain;

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

    public function setApiToken($domain)
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
        return $this;
    }

    public function getSelf()
    {
        return Bot::query()
            ->withTrashed()
            ->with([/*"botUsers",*/ "company", "imageMenus", "company.locations"])
            ->where("bot_domain", $this->domain)
            ->first();
    }

    public function setBot($bot)
    {
        $this->domain = $bot->bot_domain;
        return $this;
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

    protected function prepareTemplatePage($page, $channel = null)
    {

        $channel = is_null($channel) ? $this->chatId : $channel;

        $this->recursivePages = ($this->recursivePages ?? []);

        if (is_null($page))
            return;

        if (!in_array($page->id, $this->recursivePages))
            $this->recursivePages[] = $page->id;
        else
            return;

        $bot = $this->getSelf();

        if ($page->is_external) {
            $this->reply("Передано на внешнее управление (тестовый режим)");

            $callbackUrl = $bot->callback_link ?? null;

            if (is_null($callbackUrl))
                return;

            $external = BotExternalRequest::query()
                ->where("bot_id", $bot->id)
                ->where("bot_user_id", $this->botUser->id)
                ->whereNull("completed_at")
                ->first();

            if (is_null($external))
                BotExternalRequest::query()->create([
                    "bot_id" => $bot->id,
                    "bot_user_id" => $this->botUser->id,
                    "command" => $page->slug->command ?? null,
                    "completed_at" => null
                ]);

            $this->replyAction("typing");

            try {
                $data = Http::connectTimeout(3)->get($callbackUrl);

                Log::info($data->body());
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }

            return;
        }

        $botUser = $this->currentBotUser();
        $slug = BotMenuSlug::query()->find($page->bot_menu_slug_id);

        if ($page->need_log_user_action ?? false) {
            $thread = $bot->topics["response"] ?? null;
            $botDomain = $this->getSelf()->bot_domain;
            $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $this->currentBotUser()->telegram_chat_id);

            $pageName = is_null($slug) ? "Не указано имя страницы" : ($slug->command ?? $slug->id ?? '-');
            $tgDomain = $botUser->username ?? null;
            $tgName = $botUser->name ?? $botUser->fio_from_telegram ?? $botUser->telegram_chat_id;

            $this->sendInlineKeyboard($bot->order_channel ?? null,
                "#лог_действий_на_странице\n" .
                (!is_null($tgDomain) ? "Действие от @$tgDomain:\n" : "Действие от $tgName:\n") . "Страница: $pageName",
                [
                    [
                        ["text" => "Написать пользователю сообщение", "url" => $link]
                    ]
                ],
                $thread
            );
        }

        if (!is_null($page->password ?? null)) {
            $path = env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route&page_id=$page->id#/s/enter-page-password";

            $action = ActionStatus::query()
                ->where("user_id", $botUser->user_id)
                ->where("bot_id", $bot->id)
                ->where("slug_id", $slug->id)
                ->first();

            if (is_null($action))
                $action = ActionStatus::query()
                    ->create([
                        'user_id' => $botUser->user_id,
                        'bot_id' => $bot->id,
                        'slug_id' => $slug->id,
                        'max_attempts' => 1,
                        'current_attempts' => 0,
                        'data' => (object)[
                            "activate_at" => null,
                        ],
                        'bot_user_id' => $botUser->id
                    ]);

            $isActivated = $action->data["activate_at"] ?? null;

            if (is_null($isActivated)) {
                $this->replyInlineKeyboard($page->password_description ?? 'Страница защищена ключом!', [
                    [
                        ["text" => "💎Ввести ключ",
                            "web_app" => [
                                "url" => $path
                            ]
                        ],
                    ]
                ]);
                return;
            }

        }


        if (!is_null($page->price ?? null)) {

            $action = ActionStatus::query()
                ->where("user_id", $botUser->user_id)
                ->where("bot_id", $bot->id)
                ->where("slug_id", $slug->id)
                ->first();

            if (is_null($action))
                $action = ActionStatus::query()
                    ->create([
                        'user_id' => $botUser->user_id,
                        'bot_id' => $bot->id,
                        'slug_id' => $slug->id,
                        'max_attempts' => 1,
                        'current_attempts' => 0,
                        'data' => (object)[
                            "payed_at" => null,
                        ],
                        'bot_user_id' => $botUser->id
                    ]);

            $isPayed = $action->data["payed_at"] ?? null;

            if (is_null($isPayed)) {

                $price = $page->price * 100;

                $prices = [
                    [
                        "label" => "Оплата электронного контента",
                        "amount" => $price
                    ]
                ];

                $payload = bin2hex(Str::uuid());

                $providerToken = $bot->payment_provider_token;
                $currency = "RUB";

                Transaction::query()->create([
                    'user_id' => $botUser->user_id,
                    'bot_user_id' => $botUser->id,
                    'bot_id' => $bot->id,
                    'payload' => $payload,
                    'currency' => $currency,
                    'total_amount' => $price,
                    'status' => 0,
                    'products_info' => (object)[
                        "page_id" => $page->id ?? null,
                        "payload" => $payloadData ?? null,
                        "prices" => $prices,
                    ],
                ]);

                $needs = [
                    "need_name" => false,
                    "need_phone_number" => false,
                    "need_email" => false,
                    "need_shipping_address" => false,
                    "send_phone_number_to_provider" => false,
                    "send_email_to_provider" => false,
                    "is_flexible" => false,
                    "disable_notification" => false,
                    "protect_content" => false,
                ];

                $keyboard = [
                    [
                        ["text" => "💎Оплатить", "pay" => true],
                    ],

                ];

                $providerData = (object)[
                    "receipt" => [
                        (object)[
                            "description" => "Оплата электронного контента",
                            "quantity" => "1.00",
                            "amount" => (object)[
                                "value" => $price / 100,
                                "currency" => $currency
                            ],
                            "vat_code" => 1
                        ]
                    ]
                ];

                \App\Facades\BotManager::bot()
                    ->replyInvoice(
                        "Электронный контент", "Оплата доступа к контенту", $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                        $providerData
                    );
                return;
            }

        }

        $inlineKeyboard = $page->inlineKeyboard ?? null;
        $replyKeyboard = $page->replyKeyboard ?? null;

        $iMenu = is_null($inlineKeyboard) ? [] : ($inlineKeyboard->menu ?? []);
        $rMenu = is_null($replyKeyboard) ? [] : ($replyKeyboard->menu ?? []);

        $rMenuSettings = empty($rMenu) ? null : ($rMenu->settings ?? null);

        $content = str_replace(["<p>", "</p>"], "", $page->content);
        $content = str_replace(["<br>"], "\n", $content);

        $needSendReplyMenu = true;

        $name = $this->botUser->fio_from_telegram ?? $this->botUser->name ?? "Без имени";

        $content = str_replace(["{{userName}}"], $name, $content);

        $cashback = $this->botUser->cashBack->amount ?? 0;
        $content = str_replace(["{{cashback}}"], $cashback, $content);

        $content = str_replace(["{{level_1_percent}}"], $bot->level_1 ?? 0, $content);
        $content = str_replace(["{{level_2_percent}}"], $bot->level_2 ?? 0, $content);
        $content = str_replace(["{{level_3_percent}}"], $bot->level_3 ?? 0, $content);
        $content = str_replace(["{{cashback_fire_percent}}"], $bot->cashback_fire_percent ?? 0, $content);
        $content = str_replace(["{{is_admin}}"], ($this->botUser->is_admin ?? false) ? "Вы администратор" : "Вы не администратор", $content);
        $content = str_replace(["{{is_work}}"], ($this->botUser->is_work ?? false) ? "Вы за работой" : "Вы не работаете", $content);
        $content = str_replace(["{{is_vip}}"], ($this->botUser->is_vip ?? false) ? "Вы VIP-клиент" : "У вас еще нет статуса VIP", $content);
        $content = str_replace(["{{is_manager}}"], ($this->botUser->is_manager ?? false) ? "Вы менеджер" : "Вы не менеджер", $content);
        $content = str_replace(["{{is_deliveryman}}"], ($this->botUser->is_deliveryman ?? false) ? "Вы доставщик" : "Вы не доставщик", $content);
        $content = str_replace(["{{sex}}"], ($this->botUser->sex ?? false) ? "Мужчина" : "Женщина", $content);


        $telegramChatId = $this->botUser->telegram_chat_id ?? "Не указан";

        $content = str_replace(["{{telegramChatId}}"], $telegramChatId, $content);

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode("001" . $telegramChatId);
        //$content = sprintf($content);
        $qr = "<a href='https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$link'>QR-код</a>";
        $content = str_replace(["{{referralLink}}"], $link, $content);

        $content = str_replace(["{{referralQr}}"], $qr, $content);

        $needContentInReply = !empty($content);

        $images = [];
        if (is_array($page->images)) {
            $images = $page->images;
        }

        $replyMenuTitle = $page->reply_keyboard_title ?? null;

        if (!is_null($page->sticker)) {
            $this->replySticker($page->sticker);
        }

        if (count($images) > 1) {

            $media = [];
            foreach ($images as $image) {
                $media[] = [
                    "media" => env("APP_URL") . "/images-by-bot-id/" . $bot->id . "/" . $image,
                    "type" => "photo",
                    "caption" => ""
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

                $this->replyInlineKeyboard($needContentInReply ? ($content ?? 'Меню') : 'Меню', $iMenu);
                $needContentInReply = false;
            }


            if (!empty($rMenu)) {
                $this->replyKeyboard($needContentInReply ? ($content ?? 'Меню') : ($replyMenuTitle ?? 'Главное меню'), $rMenu,
                    settings: $rMenuSettings);

                $needSendReplyMenu = false;
                $needContentInReply = false;
            }


            if (!is_null($content ?? null) && $needContentInReply) {
                $this->reply($content ?? 'Меню');
                $needContentInReply = false;
            }

        }

        if (count($images) === 1) {
            try {
                $this->replyPhoto(mb_strlen($content) < 1024 ? $content : null,
                    InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $images[0]),
                    $iMenu
                );
            } catch (\Exception $e) {
                Log::info($e);
                $this->replyPhoto("Ошибочка у вас... напишите программисту:)",
                    InputFile::create(public_path() . "/images/cashman2.jpg")
                );
            }

            if (!empty($replyKeyboard))
                $this->replyKeyboard(mb_strlen($content) >= 1024 ?
                    $content ?? 'Хм, нечего отобразить...' : ($replyMenuTitle ?? 'Главное меню'), $rMenu, settings: $rMenuSettings);

            if (empty($replyKeyboard) && mb_strlen($content) >= 1024)
                $this->reply($content);
        }

        if (count($images) === 0) {
            $needContentInReply = empty($iMenu) && is_null($replyMenuTitle);

            $content = empty($content ?? '') && empty($replyMenuTitle ?? '') ? '' : $content;

            if (!$needContentInReply)
                $this->replyInlineKeyboard(strlen($content) > 0 ? $content : 'Главное меню', $iMenu);

            if (!empty($replyKeyboard) && $needSendReplyMenu)
                $this->replyKeyboard($needContentInReply ? (strlen($content) > 0 ? $content : 'Главное меню') :
                    ($replyMenuTitle ?? 'Главное меню'), $rMenu, settings: $rMenuSettings);

            if ($needContentInReply && empty($replyKeyboard)) {
                $this->reply($content ?? 'Главное меню');
            }

        }

        if (!is_null($page->videos)) {


            if (count($page->videos) == 1)
                $this->replyVideo(null, $page->videos[0]);

            if (count($page->videos) > 1 && count($page->videos) < 10) {
                $media = [];
                foreach ($page->videos as $video) {
                    $media[] = [
                        "media" => $video,
                        "type" => "video",
                        "caption" => "$video"
                    ];
                }

                $this->replyMediaGroup($media);
            }


        }

        if (!is_null($page->documents)) {

            $documents = $page->documents ?? [];
            if (count($documents) == 1)
                $this->replyDocument(null, $documents[0]);

            if (count($documents) > 1 && count($documents) < 10) {
                $media = [];
                foreach ($documents as $document) {
                    $media[] = [
                        "media" => $document,
                        "type" => "document",
                        "caption" => ""
                    ];
                }

                $this->replyMediaGroup($media);
            }


        }

        if (!is_null($page->audios)) {

            $audios = $page->audios ?? [];

            if (count($audios) == 1)
                $this->replyAudio(null, $audios[0]);

            if (count($audios) > 1 && count($audios) < 10) {
                $media = [];

                foreach ($audios as $audio) {
                    $media[] = [
                        "media" => $audio,
                        "type" => "audio",
                        "caption" => ""
                    ];
                }

                $this->replyMediaGroup($media);
            }


        }

        if (!is_null($page->next_page_id)) {
            $next = BotPage::query()
                ->find($page->next_page_id);

            $this->prepareTemplatePage($next);
        }


    }

    public function runSlug(int $slugId, $bot = null, $botUser = null): bool
    {


        if (!is_null($bot)) {
            $this->setApiToken($bot->bot_domain);

            include_once base_path('routes/bot.php');
        }


        if (!is_null($botUser)) {
            $this->setBotUser($botUser);

        }
        //   $channel = is_null($botUser) ? $this->currentBotUser()->telegram_chat_id : $botUser->telegram_chat_id;

        try {

            $slug = BotMenuSlug::query()
                ->where("id", $slugId)
                ->where("bot_id", is_null($bot) ? $this->getSelf()->id : $bot->id)
                ->first();

            if (is_null($slug)) {
                return false;
            }

            if (!is_null($slug->parent_slug_id)) {
                $config = $slug->config ?? [];

                $parentSlug = BotMenuSlug::query()
                    ->where("id", $slug->parent_slug_id)
                    ->first();
            }

            $item = Collection::make($this->slugs)
                ->where("path", ($parentSlug ?? $slug)->slug)
                ->first();

            if (!is_null($item)) {
                // $config = $slug->config ?? [];
                $config[] = [
                    "key" => "slug_id",
                    "value" => $slug->id,
                ];

                $this->tryCall($item, [],
                    $config, []);

            }


        } catch (Exception $e) {
            Log::info($e);
            return false;
        }

        return true;
    }


    public function runPage(int $pageId, $bot = null, $botUser = null): bool
    {

        if (!is_null($bot)) {
            $this->setApiToken($bot->bot_domain);
            $this->setBot($bot);
            include_once base_path('routes/bot.php');
        }


        if (!is_null($botUser)) {
            $this->setBotUser($botUser);

        }


        $channel = is_null($botUser) ? $this->currentBotUser()->telegram_chat_id : $botUser->telegram_chat_id;

        $page = BotPage::query()
            ->where("bot_id", is_null($bot) ? $this->getSelf()->id : $bot->id)
            ->where("id", $pageId)
            ->first();

        if (is_null($page)) {
            return false;
        }

        try {
            $this->prepareTemplatePage($page, $channel);

            if (!is_null($page->next_bot_menu_slug_id)) {
                $slug = BotMenuSlug::query()
                    ->where("id", $page
                        ->next_bot_menu_slug_id)
                    ->first();

                if (is_null($slug)) {
                    return false;
                }

                $item = Collection::make($this->slugs)
                    ->where("path", $slug->slug)
                    ->first();


                if (!is_null($item)) {
                    $config = $slug->config ?? [];
                    $config[] = [
                        "key" => "slug_id",
                        "value" => $slug->id,
                    ];


                    $this->tryCall($item, [],
                        $config, []);

                }
            }

            if (!is_null($page->next_bot_dialog_command_id))
                $this->startBotDialog($page->next_bot_dialog_command_id,
                    is_null($botUser) ?
                        $this->currentBotUser() :
                        $botUser
                );

            return true;
        } catch (\Exception $e) {
            Log::info($e);
            return false;
        }
    }


}
