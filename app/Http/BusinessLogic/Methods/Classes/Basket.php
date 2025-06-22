<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Enums\CashBackDirectionEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Events\CashBackEvent;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\BitrixLogicFactory;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Order;
use Carbon\Carbon;
use CdekSDK2\Exceptions\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class Basket
{
    use FoodBasket, GoodsBasket;

    private array $data;

    private Bot $bot;
    private BotUser $botUser;
    private BotMenuSlug $slug;

    private mixed $uploadedImage;

    private const PAYMENT_TYPES = ["Онлайн в боте", "Картой в заведении", "Переводом", "Наличными", "СБП"];

    public function __construct(array $data, $bot, $botUser, $slug, $uploadedImage = null)
    {
        $this->data = $data;
        $this->bot = $bot;
        $this->botUser = $botUser;
        $this->slug = $slug;
        $this->uploadedImage = $uploadedImage;

        $this->storeClientInfoAsContact();
    }

    /**
     * @return void
     */
    protected function storeClientInfoAsContact(): void
    {

        $vowels = ["(", ")", "-"];
        $filteredPhone = !is_null($this->data["phone"] ?? $this->botUser->phone ?? null) ?
            str_replace($vowels, "", $this->data["phone"] ?? $this->botUser->phone) : null;

        $this->botUser->name = $this->data["name"] ?? $this->botUser->name ?? null;
        $this->botUser->phone = $filteredPhone;
        $this->botUser->save();
    }

    private function useCashBackForPayment($discount): void
    {
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";

        if (!$useCashback)
            return;

        $adminBotUser = BotUser::query()
            ->where("bot_id", $this->bot->id)
            ->where("is_admin", true)
            ->orderBy("updated_at", "desc")
            ->first();

        if (!is_null($adminBotUser))
            BusinessLogic::administrative()
                ->setBotUser($adminBotUser)
                ->setBot($this->bot ?? null)
                ->removeCashBack([
                    "user_telegram_chat_id" => $this->botUser->telegram_chat_id,
                    "amount" => $discount ?? 0,
                    "info" => "Автоматическое списание скидки на покупку товара",
                ]);

    }

    private function prepareDiscount($summaryPrice): mixed
    {
        $promo = isset($this->data["promo"]) ? json_decode($this->data["promo"]) : null;
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";

        $maxUserCashback = $this->botUser->cashback->amount ?? 0;
        $botCashbackPercent = $this->bot->max_cashback_use_percent ?? 0;
        $cashBackAmount = ($summaryPrice * ($botCashbackPercent / 100));

        if (is_null($promo))
            $promo = (object)[
                "discount_in_percent" => false,
                "activate_price" => 0,
                "discount" => 0,
                "code" => "не указан"
            ];

        $promoDiscount = $promo->discount_in_percent ?
            $summaryPrice * ($promo->discount / 100) : $promo->discount;

        $cashbackDiscount = ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0);

        $discount = $cashbackDiscount +
            ($summaryPrice >= ($promo->activate_price ?? 0) ? $promoDiscount : 0);


        return (object)[
            "cashback" => $cashbackDiscount,
            "discount" => $discount,
            "message" => ($discount > 0 ? "Скидка: $discount руб." : "") .
                (!is_null($promo->code ?? null) ? " скидка за промокод '$promo->code' составляет $promo->discount " . ($promo->discount_in_percent ? "%" : "руб") . " (уже учтена)" : "")
        ];
    }

    private function checkWheelOfFortuneAction(): string
    {
        $actionPrize = !is_null($this->data["action_prize"] ?? null) ? json_decode($this->data["action_prize"]) : null;

        if (is_null($actionPrize))
            return "";


        /*
         *  wheel_types: [
            {
                key: "text",
                title: "Приз выдается во время заказа"
            },

            {
                key: "product_discount",
                title: "Скидка на товары, %"
            },
            {
                key: "delivery_discount",
                title: "Скидка на доставку, %"
            },
            {
                key: "cashback",
                title: "Начисление кэшбэка, руб"
            },
            {
                key: "effect_product",
                title: "Скидка на конкретный товар, %"
            }
        ],
         */
        $selectedPrizeDescription = $actionPrize->prize->description ?? 'Без описания приза';
        $selectedPrizeWinId = (!is_null($actionPrize->prize->win ?? null) ? json_decode($actionPrize->prize->win) : null)->id ?? null;
        $playedAt = $actionPrize->prize->played_at ?? null;

        $action = ActionStatus::query()
            ->find($actionPrize->action_id ?? null);


        $prizeText = "🎡Выигрыш в колесе фортуны:\n";
        if (!is_null($action)) {
            $tmpData = $action->data ?? [];
            $processedPrizes = [];

            foreach ($tmpData as $index => $item) {
                $item = (object)$item;
                $itemPrizeWinId = (!is_null($item->win ?? null) ? json_decode($item->win) : null)->id ?? null;

                if ($item->description == $selectedPrizeDescription &&
                    $itemPrizeWinId == $selectedPrizeWinId &&
                    !is_null($selectedPrizeWinId)) {

                    // Проверяем, не был ли этот приз уже обработан
                    $prizeKey = $selectedPrizeDescription . '_' . $selectedPrizeWinId;
                    if (in_array($prizeKey, $processedPrizes)) {
                        continue; // Если приз уже есть, пропускаем
                    }
                    $processedPrizes[] = $prizeKey; // Добавляем в обработанные

                    $tmpData[$index]["taked_at"] = Carbon::now();
                    $itemPrizeType = $tmpData[$index]["type"] ?? "text";
                    $itemPrizeEffectedValue = $tmpData[$index]["effect_value"] ?? 0;
                    $itemPrizeEffectedProduct = $tmpData[$index]["effect_product"] ?? null;

                    switch ($itemPrizeType) {
                        default:
                        case "text":
                            $prizeText .= "<em><b>" . ($item->description ?? '-') . "</b></em> - ручной режим выдачи\n\n";
                            break;
                        case "effect_product":
                        case "delivery_discount":
                        case "product_discount":
                            $prizeText .= "<em><b>" . ($item->description ?? '-') . "</b></em> - уже учтено (автоматически)\n\n";
                            break;
                        case "cashback":
                            $adminBotUser = BotUser::query()
                                ->where("bot_id", $this->bot->id)
                                ->where("is_admin", true)
                                ->first();

                            $userId = $this->botUser->user_id;

                            if (!is_null($adminBotUser))
                                event(new CashBackEvent(
                                    (int)$this->bot->id,
                                    (int)$userId,
                                    (int)$adminBotUser->user_id,
                                    ((float)$itemPrizeEffectedValue ?? 0),
                                    "Начисление баллов за колесо фортуны",
                                    CashBackDirectionEnum::Crediting
                                ));
                            break;
                    }
                }
            }

            $action->data = $tmpData;
            $action->save();
        }

        return $prizeText;
    }

    private function sendPaidReceiptToChannel($order, $message)
    {
        $uploadedPhoto = $this->uploadedImage;

        $hasPhoto = !is_null($uploadedPhoto);

        $whenReady = ($this->data["when_ready"] ?? "false") == "true";

        if ($hasPhoto) {
            $ext = $uploadedPhoto->getClientOriginalExtension();
            $imageName = Str::uuid() . "." . $ext;
            $uploadedPhoto->storeAs("$imageName");
        }


        $thread = $this->bot->topics["orders"] ?? null;

        $botUserTelegramChatId = $this->botUser->telegram_chat_id;
        $historyLink = "https://t.me/" . ($this->bot->bot_domain) . "?start=" . (
            !is_null($order) ?
                base64_encode("001" . ($botUserTelegramChatId) . "O" . $order->id) :
                base64_encode("001" . ($botUserTelegramChatId))
            );

        $userProfileLink = "https://t.me/" . ($this->bot->bot_domain) . "?start=" .
            base64_encode("003" . $botUserTelegramChatId);

        $channel = $this->bot->order_channel ?? $this->bot->main_channel ?? null;

        $userLink = "<a href='tg://user?id=$botUserTelegramChatId'>Перейти к чату с пользователем</a>\n";
        if ($hasPhoto)
            $tmpMessage = "#оплатачеком\n" .
                ($whenReady ? "🟢" : "🟡") . "Заказ №:" . ($order->id ?? '-') . "\n" .
                "Идентификатор клиента: " . ($botUserTelegramChatId ?? '-') . "\n" .
                "Пользователь: " . ($order->receiver_name ?? '-') . "\n" .
                "Телефон: " . ($order->receiver_phone ?? '-') . "\n\n" .
                "Пояснение к оплате: " . ($this->data["image_info"] ?? 'не указано');

        sleep(1);
        if ($hasPhoto)
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendPhoto(
                    $channel,
                    $tmpMessage,
                    InputFile::create(storage_path() . "/app/$imageName"),
                    [
                        /*   [
                               ["text" => "📜Заказ пользователя", "url" => $historyLink]
                           ],*/
                        [
                            ["text" => "✉Работа с пользователем", "url" => $userProfileLink]
                        ],

                    ]
                )->sendMessage($channel, "Детали заказа №:" . ($order->id ?? '-') . "\n$message\n$userLink");
        else
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard($channel, "#оплатаналичными\n$message\n$userLink",
                    [
                        /*  [
                              ["text" => "📜Заказ пользователя", "url" => $historyLink]
                          ],*/
                        [
                            ["text" => "✉Работа с пользователем", "url" => $userProfileLink]
                        ],

                    ],
                    $thread);

    }

    private function foodShopCheckout()
    {
        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $deliveryPrice = $this->data["delivery_price"] ?? 0;
        $distance = $this->data["distance"] ?? 0;
        $paymentType = $this->data["payment_type"] ?? 4;


        $productMessage = (!$needPickup ? "#заказдоставка\n" : "#заказсамовывоз\n");
        $productMessage .= $this->checkWheelOfFortuneAction();
        $productMessage .= $this->fsPrepareDisabilities();

        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNull("ordered_at")
            ->get();

        $summaryPrice = 0;
        $summaryCount = 0;

        $tmpOrderProductInfo = [];

        $ids = [];

        foreach ($basket as $item) {
            $product = $item->product ?? null;
            $collection = $item->collection ?? null;

            $price = 0;

            if (!is_null($product)) {
                $price = ($product->current_price ?? 0) * $item->count;
                $productMessage .= sprintf("💎%s x%s=%s руб.\n",
                    $product->title,
                    $item->count,
                    $price
                );

                $tmpOrderProductInfo[] = (object)[
                    "title" => $product->title,
                    "count" => $item->count,
                    "price" => $price,
                    'frontpad_article' => $product->frontpad_article ?? null,
                    'iiko_article' => $product->iiko_article ?? null,
                ];


                if (!in_array($product->id, $ids)) {
                    $ids[] = $product->id;
                }

            }

            if (!is_null($collection)) {
                $collectionTitles = "";

                /*
                * 'params' => (object)[
               "variant_id" => Str::uuid(),
               "ids" => $ids->toArray()
           ],
                */

                $params = is_null($item->params ?? null) ? null : (object)$item->params;

                foreach (($collection->products ?? []) as $product) {

                    if (!in_array($product->id, $params->ids ?? []))
                        continue;

                    $collectionTitles .= "-" . $product->title . "\n";

                    $tmpOrderProductInfo[] = (object)[
                        "title" => "Коллекция `" . ($collection->title) . "`: " . $product->title,
                        "count" => 1,
                        "price" => $product->current_price ?? 0,
                        'frontpad_article' => $product->frontpad_article ?? null,
                        'iiko_article' => $product->iiko_article ?? null,
                    ];

                    $price += $product->current_price ?? 0;

                    if (!in_array($product->id, $ids)) {
                        $ids[] = $product->id;
                    }

                }

                $price = $price * $item->count;
                $productMessage .= sprintf("💎Коллекция `%s` x%s=%s руб.:\n%s\n",
                    ($collection->title),
                    $item->count,
                    $price,
                    $collectionTitles,
                );


            }

            $summaryCount += $item->count;
            $summaryPrice += $price;

            $item->ordered_at = Carbon::now();
            $item->save();
        }

        $deliveryNote = $this->fsPrepareDeliveryNote();

        $discountItem = $this->prepareDiscount($summaryPrice);

        $this->useCashBackForPayment($discountItem->cashback ?? 0);


        $order = Order::query()->create([
            'bot_id' => $this->bot->id,
            'deliveryman_id' => null,
            'customer_id' => $this->botUser->id,
            'delivery_service_info' => null,//информация о сервисе доставки
            'deliveryman_info' => null,//информация о доставщике
            'product_details' => [
                (object)[
                    "from" => $this->bot->title ?? $this->bot->bot_domain ?? $this->bot->id,
                    "products" => $tmpOrderProductInfo
                ]
            ],//информация о продуктах и заведении, из которого сделан заказ
            'product_count' => $summaryCount,
            'summary_price' => $summaryPrice - $discountItem->discount,
            'delivery_price' => $deliveryPrice,
            'delivery_range' => $distance ?? 0,
            'deliveryman_latitude' => 0,
            'deliveryman_longitude' => 0,
            'delivery_note' => $deliveryNote,
            'receiver_name' => $this->data["name"] ?? 'Нет имени',
            'receiver_phone' => $this->data["phone"] ?? 'Нет телефона',
            'address' => $this->fsPrepareAddress() . "," . ($this->data["flat_number"] ?? ""),
            'receiver_latitude' => 0,
            'receiver_longitude' => 0,

            'status' => OrderStatusEnum::NewOrder->value,//новый заказ, взят доставщиком, доставлен, не доставлен, отменен
            'order_type' => OrderTypeEnum::InternalStore->value,//тип заказа: на продукт из магазина, на продукт конструктора
            'payed_at' => Carbon::now(),
        ]);

        $this->fsPrepareFrontPad($order, $tmpOrderProductInfo);

        BusinessLogic::review()
            ->setBotUser($this->botUser)
            ->setBot($this->bot)
            ->prepareReviews($order->id, $ids);


        $productMessage .= $discountItem->message ?? '';

        $productMessage .= "\nИтого: <b>" . ($summaryPrice - $discountItem->discount) . " руб.</b> за <b>$summaryCount ед.</b>";


        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $needBill = false;

        $productMessage .= $this->fsPrepareUserInfo($order, $discountItem->discount ?? 0);

        if ($deliveryPrice > 0) {
            $productMessage .= "\nДоставка: <b>" . $deliveryPrice . " руб.</b> за $distance км";
            $productMessage .= "\nИтого c доставкой: <b>" . (($summaryPrice + $deliveryPrice) - $discountItem->discount) . " руб.</b>";
        }


        switch ($paymentType) {
            case 0:
                BusinessLogic::payment()
                    ->setBot($this->bot)
                    ->setBotUser($this->botUser)
                    ->setSlug($this->slug)
                    ->checkout();
                //ссылка
                break;
            case 1:
                //картой в заведении
            case 2:
                //переводом
            case 3:
                //наличными
                $needBill = true;
                break;
            case 4:
                BusinessLogic::payment()
                    ->setBot($this->bot)
                    ->setBotUser($this->botUser)
                    ->setSlug($this->slug)
                    ->sbpForShop($order, $productMessage);

                $botDomain = $this->bot->bot_domain;
                $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $userId);

                $keyboard = [
                    [
                        ["text" => "✉Работа с заказом пользователя", "url" => $link]
                    ]
                ];

                $thread = $this->bot->topics["delivery"] ?? null;


                BotMethods::bot()
                    ->whereBot($this->bot)
                    ->sendInlineKeyboard(
                        $this->bot->order_channel ?? null,
                        "$productMessage\n",
                        $keyboard,
                        $thread
                    );
                return;

        }


        if ($needBill)
            $this->fsPrintPDFInfo(
                order: $order,
                summaryPrice: $summaryPrice,
                summaryCount: $summaryCount,
                tmpOrderProductInfo: $tmpOrderProductInfo,
                discount: $discountItem->discount
            );


        $this->sendPaidReceiptToChannel($order, $productMessage);

        $paymentInfo = sprintf((Collection::make($this->slug->config)
            ->where("key", "payment_info")
            ->first())["value"] ?? "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>",
            $userId);

        $productMessage .= "\n\n$paymentInfo";

        $this->fsSendResult($productMessage);

    }

    /**
     * @throws RequestException
     * @throws ValidationException
     */
    private function goodsShopCheckout()
    {
        $paymentType = $this->data["payment_type"] ?? 4;
        $cdek = json_decode($this->data["cdek"] ?? '{}');

       if (is_null($cdek->to->address??null))
           throw new HttpException(400, "Не указан адрес пункта выдачи");

        $productMessage = "#заказдоставка\n";
        $productMessage .= $this->checkWheelOfFortuneAction();

        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNull("ordered_at")
            ->get();

        $summaryPrice = 0;
        $summaryCount = 0;
        $package = [];
        $ids = [];


        $needAutomaticDeliveryRequest = ($data["need_automatic_delivery_request"] ?? false) == "true";

        $deliverySum = $needAutomaticDeliveryRequest ? $cdek->tariff->delivery_sum ?? 0 : 0;
        $tmpOrderProductInfo = [];

        $baseDimensions = $cdekSettings->base_dimensions ?? [
            "height" => 15,
            "width" => 15,
            "length" => 15,
            "weight" => 1,
        ];

        foreach ($basket as $item) {
            $product = $item->product ?? null;
            $collection = $item->collection ?? null;
            $price = 0;

            if (!is_null($product)) {
                $price = ($product->current_price ?? 0) * $item->count;
                $dimension = $product->dimension ?? (object)[];

                $productMessage .= sprintf(
                    "%s x%s=%s руб. (%s x %s x %s, %s грамм)\n",
                    $product->title,
                    $item->count,
                    $price,
                    ($package->width ?? 0) == 0 ? $baseDimensions["width"] : $dimension->width,
                    ($package->height ?? 0) == 0 ? $baseDimensions["height"] : $dimension->height,
                    ($package->length ?? 0) == 0 ? $baseDimensions["length"] : $dimension->length,
                    (($package->weight ?? 0) == 0 ? $baseDimensions["weight"] : $dimension->weight) * 1000,

                );

                $package[] = (object)[
                    "title" => $product->title,
                    "count" => $item->count,
                    "price" => $price,
                    "width" => ($package->width ?? 0) == 0 ? $baseDimensions["width"] : $dimension->width,
                    "height" => ($package->height ?? 0) == 0 ? $baseDimensions["height"] : $dimension->height,
                    "length" => ($package->length ?? 0) == 0 ? $baseDimensions["length"] : $dimension->length,
                    "weight" => ($package->weight ?? 0) == 0 ? $baseDimensions["weight"] : $dimension->weight,
                ];

                $ids[] = $product->id;

                $tmpOrderProductInfo[] = (object)[
                    "title" => "Информация о товаре: " . $product->title,
                    "count" => $item->count,
                    "price" => $product->current_price ?? 0,
                    'frontpad_article' => $product->frontpad_article ?? null,
                    'iiko_article' => $product->iiko_article ?? null,
                ];
            }

            if (!is_null($collection)) {
                $collectionTitles = "";
                $params = $item->params ? (object)$item->params : null;

                foreach (($collection->products ?? []) as $product) {
                    if (!in_array($product->id, $params->ids ?? [])) continue;

                    $collectionTitles .= "-" . $product->title . "\n";
                    $tmpOrderProductInfo[] = (object)[
                        "title" => "Коллекция `" . $collection->title . "`: " . $product->title,
                        "count" => 1,
                        "price" => $product->current_price ?? 0,
                        'frontpad_article' => $product->frontpad_article ?? null,
                        'iiko_article' => $product->iiko_article ?? null,
                    ];

                    $dimension = $product->dimension ?? (object)[];

                    $package[] = (object)[
                        "title" => "Коллекция `" . $collection->title . "`: " . $product->title,
                        "count" => 1,
                        "price" => $product->current_price ?? 0,
                        "width" => ($package->width ?? 0) == 0 ? $baseDimensions["width"] : $dimension->width,
                        "height" => ($package->height ?? 0) == 0 ? $baseDimensions["height"] : $dimension->height,
                        "length" => ($package->length ?? 0) == 0 ? $baseDimensions["length"] : $dimension->length,
                        "weight" => ($package->weight ?? 0) == 0 ? $baseDimensions["weight"] : $dimension->weight,
                    ];

                    $price += $product->current_price ?? 0;
                    $ids[] = $product->id;
                }

                $price *= $item->count;
                $productMessage .= sprintf(
                    "Коллекция `%s` x%s=%s руб.:\n%s\n",
                    $collection->title,
                    $item->count,
                    $price,
                    $collectionTitles
                );
            }

            $summaryCount += $item->count;
            $summaryPrice += $price;
        }

        $discountItem = $this->prepareDiscount($summaryPrice);
        $this->useCashBackForPayment($discountItem->discount ?? 0);

        $order = Order::query()->create([
            'bot_id' => $this->bot->id,
            'customer_id' => $this->botUser->id,
            'product_details' => [
                (object)[
                    "from" => $this->bot->title ?? $this->bot->bot_domain ?? $this->bot->id,
                    "products" => $tmpOrderProductInfo
                ]
            ],
            'product_count' => $summaryCount,
            'summary_price' => $summaryPrice,
            'delivery_price' => $deliverySum ?? 0,
            'delivery_range' => 0,
            'receiver_name' => $this->data["name"] ?? 'Нет имени',
            'receiver_phone' => $this->data["phone"] ?? 'Нет телефона',
            'address' => $this->gsPrepareFromAddress(),
            'status' => OrderStatusEnum::NewOrder->value,
            'order_type' => OrderTypeEnum::InternalStore->value,
            'payed_at' => Carbon::now(),
        ]);

        $productMessage .= "Заказ <b>#$order->id</b>\n";

        $cdekSettings = !is_null($this->bot->cdek->config ?? null) ? (object)$this->bot->cdek->config : null;

        $result = BusinessLogic::cdek()
            ->setBot($this->bot)
            ->createOrder([
                "tariff" => $cdek->tariff ?? null,
                "sender_name" => $this->bot->company->title ?? $this->bot->bot_domain ?? 'Отправитель',
                "recipient_name" => $this->data["name"] ?? $this->botUser->fio_from_telegram ?? $this->botUser->telegram_chat_id ?? null,
                "recipient_phones" => [$this->data["phone"] ?? null],
                "to" => $cdek->to ?? null,
                "from" => (object)[
                    "region" => $cdekSettings->region ?? null,
                    "city" => $cdekSettings->city ?? null,
                    "office" => $cdekSettings->office ?? null,
                ],
                "packages" => $package,
            ], $order->id);


        $bitrixLeadId = BusinessLogic::bitrix()
            ->setBot($this->bot)
            ->setBotUser($this->botUser)
            ->createDeal();

        if (!is_null($bitrixLeadId))
            BusinessLogic::bitrix()
                ->setBot($this->bot)
                ->setBotUser($this->botUser)
                ->addProducts([
                    "lead_id" => $bitrixLeadId,
                    "products" => $package,
                    "delivery" => $deliverySum
                ]);


        BusinessLogic::review()
            ->setBotUser($this->botUser)
            ->setBot($this->bot)
            ->prepareReviews($order->id, $ids);

        $productMessage .= $discountItem->message ?? '';
        $productMessage .= "\n\nТовар можно забрать в: <b>" . ($cdek->to->office->location->address_full ?? 'Неизвестный адрес') . "</b>\n";
        $productMessage .= "График работы: <b>" . ($cdek->to->office->work_time ?? 'время не указано') . "</b>\n";
        $productMessage .= "Срок доставки от <b>" . ($cdek->tariff->calendar_min ?? 1) . "</b> до <b>" . ($cdek->tariff->calendar_max ?? 7) . "</b> дней\n";

        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        if ($paymentType == 0 || $paymentType == 4) {
            BusinessLogic::payment()
                ->setBot($this->bot)
                ->setBotUser($this->botUser)
                ->setSlug($this->slug)
                ->sbpForShop($order, $productMessage);

            $botDomain = $this->bot->bot_domain;
            $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $userId);

            $keyboard = [
                [
                    ["text" => "✉Работа с заказом пользователя", "url" => $link]
                ]
            ];

            $thread = $this->bot->topics["delivery"] ?? null;

            //  $productMessage .= $this->gsPrepareUserInfo();

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard(
                    $this->bot->order_channel ?? null,
                    "$productMessage\n",
                    $keyboard,
                    $thread
                );

            return;
        }

        $productMessage .= $this->gsPrepareFromInfo($order, $discountItem->discount ?? 0);

        //  $this->gsPrintPDFInfo($order, $summaryPrice, $summaryCount, $tmpOrderProductInfo, $discountItem->discount ?? 0);
        $this->gsSendResult($productMessage);
        $this->sendPaidReceiptToChannel($order, $productMessage);
    }


    /**
     * @throws ValidationException
     */
    public function handler(): void
    {

        $displayType = $this->data["display_type"] ?? 0;

        switch ($displayType) {
            default:
            case 0:
                $this->foodShopCheckout();
                break;
            case 1:
                $this->goodsShopCheckout();
                break;
        }

    }
}
