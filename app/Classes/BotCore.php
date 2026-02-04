<?php

namespace App\Classes;

use App\Enums\BotStatusEnum;
use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Facades\InlineQueryService;
use App\Models\ActionStatus;
use App\Models\Basket;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotNote;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\ChatLog;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use PHPUnit\Exception;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

abstract class BotCore
{
    use  BotWebInterfaceTrait;

    protected $domain;

    protected $controller = null;

    protected $bot;

    protected $chatId;

    protected $inline = null;

    protected $routes = [];

    protected $slugs = [];

    protected $next = [];

    protected abstract function currentBotUser();

    protected abstract function createUser($data);

    protected abstract function setWebhooks();

    protected abstract function setApiToken($domain);

    protected abstract function checkIsWorking();

    protected abstract function getSelf();

    protected abstract function prepareTemplatePage($page, $channel = null);

    protected abstract function checkTemplatePageRules($page);

    protected abstract function botStatusHandler(): BotStatusEnum;

    protected abstract function checkIsUserBlocked(): bool;

    protected abstract function nextBotDialog($text): void;

    protected abstract function startBotDialog($dialogCommandId, $botUser = null): void;

    protected abstract function currentBotUserInDialog(): bool;

    protected abstract function stopBotDialog(): void;


    public function getCurrentChatId()
    {
        return $this->chatId;
    }

    public function tryCall($item, $message, $config = null, ...$arguments)
    {

        //$config = is_null($config) ? null : json_decode($config);

        $find = false;
        try {
            if (is_callable($item["function"])) {
                app()->call($item["function"], [$message, $config, ... $arguments]);
            } else {
                app()->call((!is_null($item["controller"]) ?
                    $item["controller"] . "@" . $item["function"] :
                    $item["function"]),
                    [$message, $config, ... $arguments]);
            }


            $find = true;
        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }

        return $find;
    }

    public function inlineHandler($data)
    {
        if (is_null($this->inline))
            return;


        $inlineData = $data["inline_query"];

        $this->chatId = $data["from"]["id"] ?? null;

        /*        $id = $data["inline_query"]["id"] ?? null;
                $query = $data["inline_query"]["query"] ?? null;
                $offset = $data["inline_query"]["offset"] ?? null;

                $this->chatId = $data["inline_query"]["from"]["id"] ?? null;*/

        InlineQueryService::inline()
            ->setBot($this->getSelf())
            ->handler($inlineData);

        //  $this->tryCall($this->inline, $query, null, $id, $offset);
    }

    public function webHandler($domain, $data): \Illuminate\Http\JsonResponse
    {
        $this->isWebMode = true;

        $this->setApiToken($domain);

        $query = $data->message ?? $data->query ?? null;

        $user = (object)$data->user;
        $this->chatId = $user->id;

        include_once base_path('routes/bot.php');

        $this->createUser($user);

        $botStatus = $this->botStatusHandler();

        if ($botStatus != BotStatusEnum::Working)
            return $this->webMessages;

        /*    if ($this->botDialogStartHandler($data, $query))
                return response()->json($this->webMessages);*/

        if ($this->botTemplatePageHandler($data, $query))
            return response()->json($this->webMessages);

        if ($this->botSlugHandler($data, $query))
            return response()->json($this->webMessages);

        if ($this->botRouteHandler($data, $query))
            return response()->json($this->webMessages);

        if ($this->botNextHandler($data))
            return response()->json($this->webMessages);

        if ($this->botFallbackPhotoHandler($data))
            return response()->json($this->webMessages);

        if ($this->botFallbackHandler($data))
            return response()->json($this->webMessages);

        /* if ($this->adminNotificationHandler($data->message, $query))
             return response()->json($this->webMessages);*/

        $this->reply("Ошибка обработки данных!");

        return response()->json($this->webMessages);
    }

    private function botLocationHandler($coords, $message): bool
    {
        if (is_null($coords))
            return false;

        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "location")
                try {
                    $item["function"]($message, (object)[
                        "lat" => $coords->latitude,
                        "lon" => $coords->longitude
                    ]);
                    return true;
                } catch (\Exception $e) {
                    Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
                }
        }

        return false;
    }

    private function botSlugHandler($message, $query): bool
    {
        $matches = [];
        $arguments = [];

        $find = false;
        foreach ($this->slugs as $item) {
            if (is_null($item["path"]) || $item["is_service"])
                continue;
            $slug = $item["path"];

            $parentSlug = BotMenuSlug::query()
                // ->where("bot_id", $this->getSelf()->id)
                ->where("slug", $slug)
                ->where("is_global", true)
                ->whereNull("bot_id")
                ->first();

            $templates = [];

            if (!is_null($parentSlug)) {
                $templates = BotMenuSlug::query()
                    ->where("bot_id", $this->getSelf()->id)
                    ->where("parent_slug_id", $parentSlug->id)
                    ->orderBy("updated_at", "DESC")
                    ->get();

            }

            if (count($templates) == 0)
                $templates =
                    BotMenuSlug::query()
                        ->where("bot_id", $this->getSelf()->id)
                        ->where("slug", $slug)
                        ->orderBy("updated_at", "DESC")
                        ->get();

            if (count($templates) == 0)
                continue;

            foreach ($templates as $template) {
                $command = $template->command;

                if (!str_starts_with($command, "/"))
                    $command = "/" . $command;
                if (preg_match($command . "$/i", $query, $matches)) {
                    foreach ($matches as $match)
                        $arguments[] = $match;

                    $config = $template->config ?? [];

                    $config[] = [
                        "key" => "slug_id",
                        "value" => $template->id,
                    ];


                    // $this->selfScriptDiagnostic($template);

                    $find = $this->tryCall($item, $message, $config, ...$arguments);
                    break;
                }
            }
            if ($find)
                return true;

        }
        return $find;
    }

    private function botTemplatePageHandler($message, $query): bool
    {

        $config = $this->getSelf()->config ?? [];

        $subscriptions = json_decode($config["subscriptions"] ?? '[]');

        $testSubscriptionActive = $subscriptions->is_active ?? false;

        if ($testSubscriptionActive) {
            $channelIds = array_column($subscriptions->channels, 'id');

            $result = $this->testChannels($channelIds);
            $text = $subscriptions->text ?? 'Проверка подписки';
            if (!$result) {

                $keyboard = collect($subscriptions->channels)
                    ->filter(fn($ch) => !empty($ch->title) && !empty($ch->link))
                    ->map(fn($ch) => [
                        [
                            'text' => $ch->title,
                            'url'  => "https://t.me/".str_replace('@', '', $ch->link),
                        ]
                    ])
                    ->values()
                    ->all();

                $keyboard[] = [
                    [
                        'text' => 'Проверить подписку',
                        'callback_data' => '/start',
                    ]
                ];

                $this->replyInlineKeyboard($text, $keyboard);
                return true;
            }

        }


        $matches = [];

        $templates = BotMenuSlug::query()
            ->with(["page"])
            ->has('page')
            ->where("bot_id", $this->getSelf()->id)
            ->get();

        $find = false;

        foreach ($templates as $template) {

            $command = $template->command;

            if (!str_starts_with($command, "/"))
                $command = "/" . $command;

            try {
                if (preg_match($command . "$/i", $query, $matches)) {
                    $find = true;
                    break;
                }

                if ($command == $query) {
                    $find = true;
                    break;
                }
            } catch (\Exception $e) {

            }

        }

        if ($find) {
            $page = $template->page;
            if (!is_null($template->page->rules_if)) {
                $result = $this->checkTemplatePageRules($page);
                if (!$result)
                    $page = !is_null($page->rules_else_page_id) ?
                        BotPage::query()
                            ->find($page->rules_else_page_id)
                        : null;
            }

            if (is_null($page))
                return true;

            $this->prepareTemplatePage($page);


            if (!is_null($page->next_bot_menu_slug_id)) {
                $slug = BotMenuSlug::query()
                    ->where("id", $page
                        ->next_bot_menu_slug_id)
                    ->first();

                if (is_null($slug))
                    return true;

                if (is_null($slug->parent_slug_id)) {
                    $item = Collection::make($this->slugs)
                        ->where("path", $slug->slug)
                        ->first();
                } else {
                    $parentSlug = BotMenuSlug::query()
                        ->find($slug->parent_slug_id);

                    if (is_null($parentSlug))
                        return true;


                    $item = Collection::make($this->slugs)
                        ->where("path", $parentSlug->slug)
                        ->first();
                }


                if (!is_null($item)) {

                    $config = $slug->config ?? [];

                    $config[] = [
                        "key" => "slug_id",
                        "value" => $slug->id,
                    ];


                    $config[] = [
                        "key" => "parent_page_id",
                        "value" => $page->id ?? null,
                    ];


                    $this->tryCall($item, $message,
                        $config, []);

                }
            }

            if (!is_null($page->next_bot_dialog_command_id)) {
                $this->startBotDialog($page->next_bot_dialog_command_id);
                return true;
            }

        }


        return $find;
    }

    /*  private function botDialogStartHandler($message, $query): bool
      {
          $matches = [];

          $templates = BotMenuSlug::query()
              ->with(["page"])
              ->whereNotNull("bot_dialog_command_id")
              ->where("bot_id", $this->getSelf()->id)
              ->get();


          foreach ($templates as $template) {

              $command = $template->command;

              if (!str_starts_with($command, "/"))
                  $command = "/" . $command;

              if (preg_match($command . "$/i", $query, $matches)) {

                  $this->startBotDialog($template->bot_dialog_command_id ?? null);
                  return true;
              }

          }
          return false;
      }*/

    private function botRouteHandler($message, $query): bool
    {
        $find = false;
        $matches = [];
        $arguments = [];

        foreach ($this->routes as $item) {

            if (is_null($item["path"]) || $item["is_service"])
                continue;

            $reg = $item["path"];

            if (!str_starts_with($reg, "/"))
                $reg = "/" . $reg;

            if (preg_match($reg . "$/i", $query, $matches)) {
                foreach ($matches as $match)
                    $arguments[] = $match;

                $find = $this->tryCall($item, $message, null, ...$arguments);
                break;
            }

        }

        return $find;
    }

    private function botNextHandler($message): bool
    {
        $find = false;
        if (!empty($this->next)) {
            foreach ($this->next as $item) {
                $find = $this->tryCall($item, $message);
            }
        }

        return $find;
    }

    private function botFallbackHandler($message): bool
    {
        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback") {
                $find = $this->tryCall($item, $message);
            }
        }
        return $find;
    }

    private function botFallbackVideoHandler($message): bool
    {
        $video = $message->video ?? $message->video_note ?? null;
        $caption = $message->caption ?? null;

        $type = isset($message->video) ? "video" : "video_note";


        if (is_null($video))
            return false;

        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback_video") {
                $find = $this->tryCall($item, $message, null, ($caption ?? null), $video, $type);
            }
        }
        return $find;
    }

    private function botFallbackAudioHandler($message): bool
    {
        $audio = $message->audio ?? $message->voice ?? null;
        $caption = $message->caption ?? $message->audio->title ?? $message->audio->file_name ?? null;

        $type = !is_null($message->audio ?? null) ? "audio" : "voice";


        if (is_null($audio))
            return false;

        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback_audio") {
                $find = $this->tryCall($item, $message, null, ($caption ?? null), $audio, $type);
            }
        }
        return $find;
    }

    private function botFallbackStickerHandler($message): bool
    {
        $sticker = $message->sticker ?? null;
        $caption = $message->caption ?? null;

        $type = "sticker";


        if (is_null($sticker))
            return false;

        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback_sticker") {
                $find = $this->tryCall($item, $message, null, ($caption ?? null), $sticker, $type);
            }
        }
        return $find;
    }

    private function botFallbackDocumentHandler($message): bool
    {
        $document = $message->document ?? $message->animation ?? null;
        $caption = $message->caption ?? $message->document->file_name ?? $message->animation->file_name ?? null;

        $type = "document";


        if (is_null($document))
            return false;

        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback_document") {
                $find = $this->tryCall($item, $message, null, ($caption ?? null), $document, $type);
            }
        }
        return $find;
    }

    private function botFallbackPhotoHandler($message): bool
    {
        $photos = $message->photo ?? null;
        $caption = $message->caption ?? null;

        if (is_null($photos))
            return false;

        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback_photo") {
                $find = $this->tryCall($item, $message, null, ($caption ?? null), [...$photos]);
            }
        }
        return $find;
    }

    public function preCheckoutQueryHandler($data)
    {

        $preCheckoutQueryId = $data->id;
        $telegramChatId = $data->from->id;
        $totalAmount = $data->total_amount;
        $payload = $data->invoice_payload;
        $currency = $data->currency;
        $orderInfo = $data->order_info ?? null;
        // $shippingOptionId = $data->shipping_option_id ?? null;

        $transaction = Transaction::query()->where("payload", $payload)
            ->first();

        if (is_null($transaction)) {
            $this->answerPreCheckoutQuery($preCheckoutQueryId, false, 'Транзакция не найдена!');
            return;
        }

        if (!is_null($transaction->completed_at)) {
            $this->answerPreCheckoutQuery($preCheckoutQueryId, false, 'Данный товар был уже куплен вами!');
            return;
        }

        $transaction->update([
            'status' => 1,
            'order_info' => $orderInfo,
        ]);

        $this->answerPreCheckoutQuery($preCheckoutQueryId, true);
    }

    public function successfulPaymentHandler($data)
    {
        $totalAmount = $data->total_amount;
        $currency = $data->currency;
        $payload = $data->invoice_payload;
        $orderInfo = $data->order_info ?? null;
        $telegramPaymentChargeId = $data->telegram_payment_charge_id;
        $providerPaymentChargeId = $data->provider_payment_charge_id;

        $transaction = Transaction::query()
            ->with(["bot"])
            ->where("payload", $payload)
            ->first();

        $bot = $transaction->bot;
        $channel = $bot->order_channel ??
            null;

        if ($bot->auto_cashback_on_payments) {
            $tmpTotalAmount = $totalAmount / 100;

            $adminBotUser = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_admin", true)
                ->first();

            $userId = $transaction->user_id;

            if (!is_null($adminBotUser))
                event(new CashBackEvent(
                    (int)$bot->id,
                    (int)$userId,
                    (int)$adminBotUser->user_id,
                    ((float)$tmpTotalAmount ?? 0),
                    "Автоматическое начисление баллов",
                    CashBackDirectionEnum::Crediting
                ));
        }

        if (!is_null($channel)) {

            $name = $orderInfo->name ?? 'Без имени';
            $phoneNumber = $orderInfo->phone_number ?? 'Без телефона';
            $email = $orderInfo->email ?? 'Без почты';

            $productInfo = (object)$transaction->products_info;

            $tmpTotalAmount = $totalAmount / 100;

            $payload = $productInfo->payload ?? 'Артикул товара не указан администратором';

            $data = "";

            foreach ($productInfo->prices as $item) {
                $item = (object)$item;
                $price = $item->amount / 100;
                $data .= "$item->label по цене $price руб.,\n";
            }

            $this->sendMessage($channel, "Пользователь  $name ($phoneNumber , $email) совершил оплату $tmpTotalAmount руб. за продукт:\n <em>$data</em>\n('$payload')");

        }

        $botUser = BotManager::bot()->currentBotUser();

        $baskets = Basket::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->whereNull("ordered_at")
            ->get();

        foreach ($baskets as $basket) {
            $basket->ordered_at = Carbon::now();
            $basket->save();
        }


        $pageId = $transaction->products_info["page_id"] ?? null;
        $product = $transaction->products_info["payload"] ?? 'не указан продавцом';

        if (!is_null($pageId)) {
            $page = BotPage::query()
                ->where("id", $pageId)
                ->first();

            $period = $page->price_period ?? 1;

            $action = ActionStatus::query()
                ->where("user_id", $botUser->user_id)
                ->where("bot_id", $bot->id)
                ->where("slug_id", $page->bot_menu_slug_id ?? null)
                ->first();

            if (is_null($action))
                return;

            $action->data = (object)[
                "payed_at" => Carbon::now(),
                "payed_until"=>Carbon::now()->addDays($period)
            ];
            $action->save();

            \App\Facades\BotManager::bot()
                ->runPage($page->id, $bot, $botUser);

            $transaction->update([
                'status' => 2,
                'order_info' => $orderInfo,
                'telegram_payment_charge_id' => $telegramPaymentChargeId,
                'provider_payment_charge_id' => $providerPaymentChargeId,
                'completed_at' => Carbon::now()
            ]);
            return;
        }

        \App\Facades\BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage($botUser->telegram_chat_id, "Ваша покупка:\n$product");


        $transaction->update([
            'status' => 2,
            'order_info' => $orderInfo,
            'telegram_payment_charge_id' => $telegramPaymentChargeId,
            'provider_payment_charge_id' => $providerPaymentChargeId,
            'completed_at' => Carbon::now()
        ]);

        $mpdf = new Mpdf();
        $current_date = Carbon::now("+3:00")->format("Y-m-d H:i:s");

        $number = Str::uuid();


        $mpdf->WriteHTML(view("pdf.draft", [
            "title" => $this->bot->title ?? $this->bot->bot_domain ?? 'CashMan',
            "date" => $current_date,
            "completed_at" => $transaction->completed_at ?? null,
            "payload" => $transaction->payload,
            "currency" => $transaction->currency,
            "total_amount" => $transaction->total_amount ?? 0,
            "status" => $transaction->status ?? 0,
            "order_info" => $transaction->order_info ?? null,
            "products_info" => $transaction->products_info ?? null,
            "shipping_address" => $transaction->shipping_address ?? null,
            "telegram_payment_charge_id" => $transaction->telegram_payment_charge_id ?? null,
            "provider_payment_charge_id" => $transaction->provider_payment_charge_id ?? null,
        ]));

        $file = $mpdf->Output("order-$number.pdf", \Mpdf\Output\Destination::STRING_RETURN);


        $this->replyDocument(
            "Чек #" . ($transaction->id ?? 'не указан'),
            InputFile::createFromContents($file, "draft.pdf")
        );
    }

    public function shippingQueryHandler($data)
    {

        $answerShippingQuery = $data->id;
        $telegramChatId = $data->from->id;
        $payload = $data->invoice_payload;
        $shippingAddress = $data->shipping_address;

        //Log::info("shippingQueryHandler" . print_r($data, true));

        $transaction = Transaction::query()->where("payload", $payload)
            ->first();

        $transaction->update([
            'shipping_address' => $shippingAddress,
        ]);

        $this->answerShippingQuery($answerShippingQuery, true);
    }


    public function handler($domain)
    {
        $this->setApiToken($domain);

        if (is_null($this->bot))
            return;

        $update = $this->bot->getWebhookUpdate();


        //Log::info(print_r($update, true));

        include_once base_path('routes/bot.php');

        $item = json_decode($update);

        if (isset($update["channel_post"])) {

            $text = $item->channel_post->text ?? null;

            if (mb_strtolower($text) === "мой id")
                \App\Facades\BotMethods::bot()
                    ->whereDomain($this->getSelf()->bot_domain)
                    ->sendMessage($item->channel_post->sender_chat->id,
                        "Ваш id=>" . $item->channel_post->sender_chat->id
                    );
            return;
        }

        if (isset($update["inline_query"])) {
            $this->createUser($item->inline_query->from);
            $this->inlineHandler($update);
            return;
        }
        try {
            if (isset($update["pre_checkout_query"])) {
                $this->preCheckoutQueryHandler($item->pre_checkout_query);
                return;
            }

            if (isset($update["shipping_query"])) {
                $this->shippingQueryHandler($item->shipping_query);
                return;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }


        //формируем сообщение из возможных вариантов входных данных
        $message = $item->message ??
            $item->edited_message ??
            $item->callback_query->message ??

            null;

        //если сообщения нет, то завершаем работу
        if (is_null($message))
            return;


        //разделяем логику получения данных об отправителе,
        // так как данные приходят в разных частях JSON-объекта,
        // то создадим условие, по которому будем различать откуда получать эти данные

        if (isset($update["callback_query"]))
            $this->createUser($item->callback_query->from);
        else
            $this->createUser($message->from);

        if ($this->checkIsUserBlocked())
            return;

        try {
            if (isset($update["message"]["successful_payment"])) {
                $this->successfulPaymentHandler($item->message->successful_payment);
                return;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }


        try {


            $query = $item->message->text ??
                $item->callback_query->data ??
                $item->message->contact->phone_number ?? '';

            if (!is_null($item->message->contact ?? null)) {
                $botUser = $this->currentBotUser();
                $botUser->phone = $item->message->contact->phone_number ?? $botUser->phone ?? null;
                $botUser->save();

                $actions = ActionStatus::query()
                    ->where("user_id", $this->currentBotUser()->user_id)
                    ->where("bot_id", $this->getSelf()->id)
                    ->orderBy("created_at", "desc")
                    ->get();

                if (count($actions ?? []) > 0) {
                    foreach ($actions as $action) {

                        $tmpData = (array)$action->data;
                        $success = array_key_exists("cashback_at", $tmpData) && is_null($tmpData["cashback_at"] ?? null);


                        if ($success) {
                            $page = BotPage::query()
                                ->where("bot_id", $action->bot_id)
                                ->where("bot_menu_slug_id", $action->slug_id)
                                ->first();

                            $cashback = !is_null($page) ? $page->cashback ?? 0 : 0;

                            $adminBotUser = BotUser::query()
                                ->where("bot_id", $action->bot_id)
                                ->where("is_admin", true)
                                ->first();

                            if (!is_null($adminBotUser)) {
                                $action->data = (object)[
                                    "cashback_at" => Carbon::now(),
                                ];
                                $action->save();

                                event(new CashBackEvent(
                                    (int)$action->bot_id,
                                    (int)$this->currentBotUser()->user_id,
                                    (int)$adminBotUser->user_id,
                                    $cashback,
                                    "Начисление бонусов за переход",
                                    CashBackDirectionEnum::Crediting,
                                    100,
                                    false
                                ));

                                BotManager::bot()
                                    ->runPage($page->id, $this->getSelf(), $this->currentBotUser());
                                break;
                            }
                        }


                    }
                }

                BusinessLogic::bitrix()
                    ->setBot($this->getSelf())
                    ->setBotUser($this->currentBotUser())
                    ->addLead("Отправил свой номер в бот");
            }


            $this->chatId = $message->chat->id;

            $botStatus = $this->botStatusHandler();

            if ($botStatus->value != BotStatusEnum::Working->value)
                return;


            $coords = !isset($update["message"]["location"]) ? null :
                (object)[
                    "latitude" => $update["message"]["location"]["latitude"] ?? 0,
                    "longitude" => $update["message"]["location"]["longitude"] ?? 0
                ];

            if ($this->currentBotUserInDialog()) {
                $this->nextBotDialog($query);
                return;
            }

            if ($this->botLocationHandler($coords, $message))
                return;

            if ($this->botTemplatePageHandler($message, $query))
                return;

            /*   if ($this->botDialogStartHandler($message, $query))
                   return;*/

            if ($this->botSlugHandler($message, $query))
                return;

            if ($this->botRouteHandler($message, $query))
                return;

            if ($this->botNextHandler($message))
                return;


            if ($this->botFallbackStickerHandler($message))
                return;

            if ($this->botFallbackPhotoHandler($message))
                return;

            if ($this->botFallbackVideoHandler($message))
                return;

            if ($this->botFallbackAudioHandler($message))
                return;

            if ($this->botFallbackDocumentHandler($message))
                return;

            if ($this->botFallbackHandler($message))
                return;



            if ($this->adminNotificationHandler($message, $query))
                return;

            if (($update["message"]["chat"]["is_forum"] ?? 0) == 0)
                $this->reply("Ошибка обработки данных!");
        } catch (Exception $e) {
            Log::info("in handler function=>" . $e->getMessage() . " " . $e->getFile() . " " . $e->getLine());

        }
    }


    public function pushCommand(string $command): void
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        //если сообщения нет, то завершаем работу
        if (is_null($command))
            return;


        include_once base_path('routes/bot.php');

        if ($this->botTemplatePageHandler(null, $command))
            return;

        if ($this->botSlugHandler(null, $command))
            return;

        if ($this->botRouteHandler(null, $command))
            return;

        $this->reply("Ошибка обработки данных!");
    }

    private function addMessageToJson($filename, $newMessage): string
    {
        $path = "chat-logs/$filename.json";

        $type = "new";

        // Проверка существования файла
        if (Storage::exists($path)) {
            // Чтение и декодирование файла
            $json = Storage::get($path);
            $data = json_decode($json, true);

            // Проверка наличия ключей
            $data['bot_id'] = $data['bot_id'] ?? null;
            $data['channel'] = $data['channel'] ?? null;
            $data['link'] = $data['link'] ?? null;
            $data['thread'] = $data['thread'] ?? null;
            $data['user'] = $data['user'] ?? null;
            $data['timestamp'] = $newMessage["timestamp"] ?? Carbon::now()->timestamp;
            $data['messages'] = $data['messages'] ?? [];
            // Добавление нового сообщения
            $data['messages'][] = [
                "message" => $newMessage["message"] ?? '-',
                'timestamp' => $newMessage["timestamp"] ?? null
            ];

            $type = "append";
        } else {
            // Создание новой структуры
            $data = [
                'bot_id' => $newMessage["bot_id"] ?? null,
                'channel' => $newMessage["channel"] ?? null,
                'thread' => $newMessage["thread"] ?? null,
                'link' => $newMessage["link"] ?? null,
                'user' => $newMessage["user"] ?? null,
                'timestamp' => $newMessage["timestamp"] ?? Carbon::now()->timestamp,
                'messages' => [[
                    "message" => $newMessage["message"] ?? '-',
                    'timestamp' => $newMessage["timestamp"] ?? null
                ]],
            ];
        }

        // Сохранение обратно в файл
        Storage::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $type;
    }

    public function adminNotificationHandler($message, $query): bool
    {
        $type = $message->chat->type ?? null;

        if (is_null($type) || $type == "supergroup")
            return true;

        $botUser = $this->currentBotUser();
        $name = $botUser->name ?? $botUser->fio_from_telegram ?? $botUser->telegram_chat_id;

        $channel = $this->getSelf()->order_channel ?? null;
        if (!is_null($channel)) {
            $botDomain = $this->getSelf()->bot_domain;
            $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $this->currentBotUser()->telegram_chat_id);

            $thread = $this->getSelf()->topics["response"] ?? null;

            if (strlen($channel) > 6 && str_starts_with($channel, "-")) {

                $result = $this->addMessageToJson("chat-history-" . $this->currentBotUser()->telegram_chat_id, [
                    "bot_id" => $this->getSelf()->id,
                    "channel" => $channel,
                    "thread" => $thread,
                    "link" => $link,
                    "user" => [
                        "name" => $name,
                        "telegram_chat_id" => $botUser->telegram_chat_id
                    ],
                    'timestamp' => now()->toDateTimeString(),
                    "message" => $query
                ]);

                if ($result == "new")
                    $this->reply("Ваши сообщения будет доставлено администратору в течении 5 минут с момента последнего вашего сообщения. Вы можете продолжить писать.");
                else
                {
                    $this->replyAction();
                }


                return true;
            }

        }
        return false;

    }

    public function next($name)
    {
        foreach ($this->routes as $route) {
            if (isset($route["name"]))
                if ($route["name"] == $name)
                    $this->next[] = [
                        "name" => $name,
                        "controller" => $this->controller ?? null,
                        "function" => $route["function"],
                        //  "arguments"=>$arguments??[]
                    ];
        }

        return $this;
    }

    public function controller($controller)
    {
        $this->controller = $controller;


        return $this;
    }

    public function route($path, $function, $name = null)
    {
        $this->routes[] = [
            "path" => $path,
            "is_service" => false,
            "controller" => $this->controller ?? null,
            "function" => $function,
            "name" => $name
        ];

        return $this;
    }

    public function slug($path, $function, $name = null)
    {
        $this->slugs[] = [
            "path" => $path,
            "is_service" => false,
            "controller" => $this->controller ?? null,
            "function" => $function,
            "name" => $name
        ];

        return $this;
    }

    public function location($function)
    {
        $this->routes[] = [
            "path" => "location",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallback($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallbackPhoto($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback_photo",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallbackAudio($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback_audio",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallbackSticker($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback_sticker",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallbackDocument($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback_document",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallbackVideo($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback_video",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function inline($function)
    {
        $this->inline = [
            "controller" => $this->controller ?? null,
            "function" => $function
        ];

        return $this;
    }

}
