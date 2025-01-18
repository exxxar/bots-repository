<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Enums\CashBackDirectionEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Events\CashBackEvent;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
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

        $promoDiscount  = $promo->discount_in_percent ?
            $summaryPrice*($promo->discount/100) : $promo->discount;

        $cashbackDiscount = ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0);

        $discount = $cashbackDiscount +
            ($summaryPrice >= ($promo->activate_price ?? 0) ? $promoDiscount : 0);


        return (object)[
            "cashback"=>$cashbackDiscount,
            "discount" => $discount,
            "message" => ($discount > 0 ? "Скидка: $discount руб." : "") .
                (!is_null($promo->code ?? null) ? " скидка за промокод '$promo->code' составляет $promo->discount ".($promo->discount_in_percent ? "%":"руб")." (уже учтена)" : "")
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
            foreach ($tmpData as $index => $item) {
                $item = (object)$item;

                $itemPrizeWinId = (!is_null($item->win ?? null) ? json_decode($item->win) : null)->id ?? null;
                if ($item->description == $selectedPrizeDescription &&
                    $itemPrizeWinId == $selectedPrizeWinId &&
                    !is_null($selectedPrizeWinId)) {

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

    private function sendPaidReceiptToChannel($order)
    {
        $uploadedPhoto = $this->uploadedImage;


        if (is_null($uploadedPhoto))
            return;

        $whenReady = ($this->data["when_ready"] ?? "false") == "true";

        $ext = $uploadedPhoto->getClientOriginalExtension();

        $imageName = Str::uuid() . "." . $ext;

        $uploadedPhoto->storeAs("$imageName");

        $thread = $this->bot->topics["orders"] ?? null;

        $historyLink = "https://t.me/" . ($this->bot->bot_domain) . "?start=" . (
            !is_null($order) ?
                base64_encode("001" . ($this->botUser->telegram_chat_id) . "O" . $order->id) :
                base64_encode("001" . ($this->botUser->telegram_chat_id))
            );

        $channel = $this->bot->order_channel ?? $this->bot->main_channel ?? null;

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendPhoto(
                $channel,
                "#оплатачеком\n" .
                ($whenReady ? "🟢" : "🟡") . "Заказ №:" . ($order->id ?? '-') . "\n" .
                "Идентификатор клиента: " . ($this->botUser->telegram_chat_id ?? '-') . "\n" .
                "Пользователь: " . ($order->receiver_name ?? '-') . "\n" .
                "Телефон: " . ($order->receiver_phone ?? '-') . "\n\n" .
                "Пояснение к оплате: " . ($this->data["image_info"] ?? 'не указано'),
                InputFile::create(storage_path() . "/app/$imageName"), [
                [
                    ["text" => "📜Заказ пользователя", "url" => $historyLink]
                ],

            ],
                $thread
            );

    }

    private function foodShopCheckout()
    {
        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $deliveryPrice = $this->data["delivery_price"] ?? 0;
        $paymentType = $this->data["payment_type"] ?? 4;

        $productMessage = (!$needPickup ? "#заказдоставка\n\n" : "#заказсамовывоз\n\n");
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

            //   $item->ordered_at = Carbon::now();
            //  $item->save();
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
            'summary_price' => $summaryPrice-$discountItem->discount,
            'delivery_price' => $deliveryPrice,
            'delivery_range' => $distance ?? 0,
            'deliveryman_latitude' => 0,
            'deliveryman_longitude' => 0,
            'delivery_note' => $deliveryNote,
            'receiver_name' => $this->data["name"] ?? 'Нет имени',
            'receiver_phone' => $this->data["phone"] ?? 'Нет телефона',
            'address' => $this->fsPrepareAddress() . "," . ($this->data["flat_number"] ?? ""),
            'receiver_latitude' => $geo->latitude ?? 0,
            'receiver_longitude' => $geo->longitude ?? 0,

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

        $productMessage .= "\nИтого: <b>".($summaryPrice-$discountItem->discount)." руб.</b> за <b>$summaryCount ед.</b> \n\n";


        $needBill = false;
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
                    ->sbpForFood($order, $productMessage);
                return;

        }

        $productMessage .= $this->fsPrepareUserInfo($order, $discountItem->discount ?? 0);

        if ($needBill)
            $this->fsPrintPDFInfo(
                order: $order,
                summaryPrice: $summaryPrice,
                summaryCount: $summaryCount,
                tmpOrderProductInfo: $tmpOrderProductInfo,
                discount: $discountItem->discount
            );

        $this->fsSendResult($productMessage);
        $this->sendPaidReceiptToChannel($order);
    }

    private function goodsShopCheckout()
    {

        dd($this->gsPrepareFromAddress());

        $paymentType = $this->data["payment_type"] ?? 4;
        $cdek = json_decode($this->data["cdek"]);

        $productMessage = "#заказдоставка\n\n";
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

        foreach ($basket as $item) {


            $product = $item->product ?? null;
            $collection = $item->collection ?? null;

            $price = 0;

            if (!is_null($product)) {
                $price = ($product->current_price ?? 0) * $item->count;

                $dimension = $product->dimension ?? null;

                $productMessage .= sprintf("💎%s x%s=%s руб. (%s x %s x %s, %s грамм)\n",
                    $product->title,
                    $item->count,
                    $price,
                    $dimension->width ?? 0,
                    $dimension->height ?? 0,
                    $dimension->length ?? 0,
                    $dimension->weight ?? 0,
                );

                $package[] = (object)[
                    "title" => $product->title,
                    "count" => $item->count,
                    "price" => $price,
                    "width" => $dimension->width ?? 0,
                    "height" => $dimension->height ?? 0,
                    "length" => $dimension->length ?? 0,
                    "weight" => $dimension->weight ?? 0,
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

            //   $item->ordered_at = Carbon::now();
            //  $item->save();
        }


        $discountItem = $this->prepareDiscount($summaryPrice);

        $this->useCashBackForPayment($discountItem->discount ?? 0);


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
            'summary_price' => $summaryPrice,
            'delivery_price' => $deliveryPrice,
            'delivery_range' => $distance ?? 0,
            'deliveryman_latitude' => 0,
            'deliveryman_longitude' => 0,
            'delivery_note' => $deliveryNote,
            'receiver_name' => $this->data["name"] ?? 'Нет имени',
            'receiver_phone' => $this->data["phone"] ?? 'Нет телефона',
            'address' => $this->gsPrepareAddress() . "," . ($this->data["flat_number"] ?? ""),
            'receiver_latitude' => $geo->latitude ?? 0,
            'receiver_longitude' => $geo->longitude ?? 0,

            'status' => OrderStatusEnum::NewOrder->value,//новый заказ, взят доставщиком, доставлен, не доставлен, отменен
            'order_type' => OrderTypeEnum::InternalStore->value,//тип заказа: на продукт из магазина, на продукт конструктора
            'payed_at' => Carbon::now(),
        ]);


        $cdekSettings = !is_null($this->bot->cdek->config ?? null) ? (object)$this->bot->cdek->config ?? null : null;


        BusinessLogic::cdek()
            ->setBot($this->bot)
            ->createOrder([
                "tariff" => $cdek->tariff,
                "sender_name" => $this->bot->company->title ??
                        $this->bot->bot_domain ??
                        'Отправитель',
                "recipient_name" => $this->data["name"] ??
                        $this->botUser->fio_from_telegram ??
                        $this->botUser->telegram_chat_id ?? null,
                "recipient_phones" => "required",
                "to" => $cdek->to,
                "from" => (object)[
                    "region" => $cdekSettings->region,
                    "city" => $cdekSettings->city,
                    "office" => $cdekSettings->office,

                ],
                "packages" => $package,
            ]);

        BusinessLogic::review()
            ->setBotUser($this->botUser)
            ->setBot($this->bot)
            ->prepareReviews($order->id, $ids);


        $productMessage .= $discountItem->message ?? '';

        $productMessage .= "\nИтого: <b>$summaryPrice руб.</b> за <b>$summaryCount ед.</b> \n\n";

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
                break;
            case 4:
                BusinessLogic::payment()
                    ->setBot($this->bot)
                    ->setBotUser($this->botUser)
                    ->setSlug($this->slug)
                    ->sbp($order, $productMessage);
                return;

        }

        $productMessage .= $this->gsPrepareUserInfo($order, $discountItem->discount ?? 0);


        $this->gsPrintPDFInfo(
            order: $order,
            summaryPrice: $summaryPrice,
            summaryCount: $summaryCount,
            tmpOrderProductInfo: $tmpOrderProductInfo,
            discount: $discountItem->discount
        );

        $this->gsSendResult($productMessage);
        $this->sendPaidReceiptToChannel($order);
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
