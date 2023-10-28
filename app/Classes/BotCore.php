<?php

namespace App\Classes;

use App\Enums\BotStatusEnum;
use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Models\Basket;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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

    protected abstract function prepareTemplatePage($page);

    protected abstract function checkTemplatePageRules($page);

    protected abstract function botStatusHandler(): BotStatusEnum;

    protected abstract function nextBotDialog($text): void;

    protected abstract function startBotDialog($dialogCommandId): void;

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


        $id = $data["inline_query"]["id"] ?? null;
        $query = $data["inline_query"]["query"] ?? null;

        $this->chatId = $data["inline_query"]["from"]["id"] ?? null;


        $this->tryCall($this->inline, $query, null, $id);
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
            $templates = BotMenuSlug::query()
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

                        $item = Collection::make($this->slugs)
                            ->where("path", $slug->slug)
                            ->first();

                        if (!is_null($item)) {

                            $config = $slug->config ?? [];

                            $config[] = [
                                "key" => "slug_id",
                                "value" => $slug->id,
                            ];


                            $this->tryCall($item, $message,
                                $config, []);

                        }
                    }

                    if (!is_null($page->next_bot_dialog_command_id)) {
                        $this->startBotDialog($page->next_bot_dialog_command_id);
                        return true;
                    }


                    $find = true;
                    break;
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
                return $find;
            }

            if ($find)
                return true;

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
            $this->answerPreCheckoutQuery($preCheckoutQueryId, false, 'Транзакция не надена!');
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
            $bot->main_channel ?? null;

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
                    "Автоматическое начисление CashBack",
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

        $transaction->update([
            'status' => 2,
            'order_info' => $orderInfo,
            'telegram_payment_charge_id' => $telegramPaymentChargeId,
            'provider_payment_charge_id' => $providerPaymentChargeId,
            'completed_at' => Carbon::now()
        ]);
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

      //  Log::info(print_r($update, true));

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

        if (!is_null($this->currentBotUser()->blocked_at ?? null)) {
            $this->reply($this->currentBotUser()->blocked_message ?? "Вам ограничен доступ!");
            return;
        }

        try {
            if (isset($update["message"]["successful_payment"])) {
                $this->successfulPaymentHandler($item->message->successful_payment);
                return;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }


        $query = $item->message->text ??
            $item->callback_query->data ?? '';

        $this->chatId = $message->chat->id;

        $botStatus = $this->botStatusHandler();

        if ($botStatus != BotStatusEnum::Working)
            return;

        if ($this->currentBotUserInDialog()) {
            $this->nextBotDialog($query);
            return;
        }

        $coords = !isset($update["message"]["location"]) ? null :
            (object)[
                "latitude" => $update["message"]["location"]["latitude"] ?? 0,
                "longitude" => $update["message"]["location"]["longitude"] ?? 0
            ];

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

        if ($this->botFallbackPhotoHandler($message))
            return;

        if ($this->botFallbackVideoHandler($message))
            return;

        if ($this->botFallbackHandler($message))
            return;

        if ($this->adminNotificationHandler($query))
            return;

        if (($update["message"]["chat"]["is_forum"] ?? 0) == 0)
            $this->reply("Ошибка обработки данных!");
    }


    public function runPage(int $pageId): void
    {

        $page = BotPage::query()
            ->where("bot_id", $this->getSelf()->id)
            ->where("id", $pageId)
            ->first();

        if (is_null($page)) {
            $this->reply("Страничка не найдена:(");
            return;
        }

        try {
            $this->prepareTemplatePage($page);

            if (!is_null($page->next_bot_menu_slug_id)) {
                $slug = BotMenuSlug::query()
                    ->where("id", $page
                        ->next_bot_menu_slug_id)
                    ->first();

                if (is_null($slug)) {
                    $this->reply("Скрипт не найден");
                    return;
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
                $this->startBotDialog($page->next_bot_dialog_command_id);

        } catch (\Exception $e) {

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

    public function adminNotificationHandler($query): bool
    {
        if (mb_strlen($query) < 10)
            return false;

        $channel = $this->getSelf()->main_channel ?? $this->getSelf()->order_channel ?? null;
        if (!is_null($channel)) {
            $domain = $this->currentBotUser()->username ?? null;
            $name = $this->currentBotUser()->name ?? $this->currentBotUser()->fio_from_telegram ?? $this->currentBotUser()->telegram_chat_id;

            $botDomain = $this->getSelf()->bot_domain;
            $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $this->currentBotUser()->telegram_chat_id);

            $thread = $this->getSelf()->topics["questions"] ?? null;

            if (strlen($channel) > 6 && str_starts_with($channel, "-")) {
                $this->sendInlineKeyboard($channel,
                    "#ответ\n" .
                    (!is_null($domain) ? "Сообщение от @$domain:\n" : "Сообщение от $name:\n") .
                    "$query",
                    [
                        [
                            ["text" => "Написать пользователю ответ", "url" => $link]
                        ]
                    ],
                    $thread
                );

                $this->reply("Ваше сообщение успешно доставлено администратору бота");
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

        try {


            if (is_subclass_of($controller, SlugController::class)) {
                app($controller)->config($this->getSelf());
            }

        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }
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
