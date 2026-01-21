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
use App\Models\Partner;
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
    //  private BotMenuSlug $slug;

    private mixed $uploadedImage;

    private const PAYMENT_TYPES = ["Онлайн в боте", "Картой в заведении", "Переводом", "Наличными", "СБП"];

    public function __construct(array $data, $bot, $botUser, $uploadedImage = null)
    {
        $this->data = $data;
        $this->bot = $bot;
        $this->botUser = $botUser;
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

    private function useCashBackForPayment($discount, $partnerId = null): void
    {
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";

        if (!$useCashback)
            return;

        $adminBotUser = BotUser::query()
            ->where("bot_id", $partnerId ?? $this->bot->id)
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

    private function prepareCashbackDiscount($summaryPrice)
    {
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";

        $maxUserCashback = $this->botUser->cashback->amount ?? 0;
        $botCashbackPercent = $this->bot->max_cashback_use_percent ?? 0;
        $cashBackAmount = ($summaryPrice * ($botCashbackPercent / 100));

        return ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0);

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

        //  $userLink = "<a href='tg://user?id=$botUserTelegramChatId'>Перейти к чату с пользователем</a>\n";
        if ($hasPhoto)
            $tmpMessage = "#оплатачеком\n" .
                ($whenReady ? "🟢" : "🟡") . "Заказ №:" . ($order->id ?? '-') . "\n" .
                "Идентификатор клиента: " . ($botUserTelegramChatId ?? '-') . "\n" .
                "Пользователь: " . ($order->receiver_name ?? '-') . "\n" .
                "Телефон: " . ($order->receiver_phone ?? '-') . "\n\n" .
                "Пояснение к оплате: " . ($this->data["image_info"] ?? 'не указано');

        sleep(1);
        if ($hasPhoto) {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendPhoto(
                    $channel,
                    $tmpMessage,
                    InputFile::create(storage_path() . "/app/$imageName"),
                    [
                        [
                            ["text" => "✉Работа с пользователем", "url" => $userProfileLink]
                        ],

                    ]
                );

            sleep(1);
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage($channel, "Детали заказа №:" . ($order->id ?? '-') . "\n$message");
        } else
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard($channel, "#оплатаналичными\n$message",
                    [

                        [
                            ["text" => "✉Работа с пользователем", "url" => $userProfileLink]
                        ],

                    ],
                    $thread);

    }

    /**
     * @throws ValidationException
     */
    private function foodShopCheckout(): ?object
    {
        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $deliveryPrice = $this->data["delivery_price"] ?? 0;
        $distance = $this->data["distance"] ?? 0;
        $paymentType = $this->data["payment_type"] ?? 4;
        $deliveryDetails = json_decode($this->data["delivery_details"] ?? '[]');
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";

        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNull("ordered_at")
            ->get();


        $summaryPrice = 0;
        $summaryCount = 0;
        $summaryDiscount = 0;

        $tmpOrderProductInfo = [];

        $partnerProductBox = [];
        $ids = [];

        foreach ($basket as $item) {
            $comment = $item->comment ?? null;
            $product = $item->product ?? null;
            $collection = $item->collection ?? null;

            $partner = $item->partner ?? $this->bot;

            $deliveryDetails = (array)$deliveryDetails;

            if (empty($partnerProductBox[$partner->bot_domain])) {
                $partnerProductBox[$partner->bot_domain]["order_channel"] = $partner->order_channel ?? null;
                $partnerProductBox[$partner->bot_domain]["id"] = $partner->id;
                $partnerProductBox[$partner->bot_domain]["title"] = $partner->title ?? $partner->bot_domain ?? 'Без названия';
                $partnerProductBox[$partner->bot_domain]["message"] = "";
                $partnerProductBox[$partner->bot_domain]["extra_charge"] = (Partner::query()
                    ->where("bot_id", $item->bot_id)
                    ->where("bot_partner_id", $item->bot_partner_id)
                    ->first())->extra_charge ?? 0;
                $partnerProductBox[$partner->bot_domain]["summary_price"] = 0;
                $partnerProductBox[$partner->bot_domain]["summary_count"] = 0;
                $partnerProductBox[$partner->bot_domain]["summary_discount"] =0;
                $partnerProductBox[$partner->bot_domain]["delivery_price"] = $deliveryDetails[$partner->bot_domain]->price ?? 0;
                $partnerProductBox[$partner->bot_domain]["distance"] = $deliveryDetails[$partner->bot_domain]->distance ?? 0;
                $partnerProductBox[$partner->bot_domain]["address"] = $deliveryDetails[$partner->bot_domain]->address ?? '-';
                $partnerProductBox[$partner->bot_domain]["thread"] = $partner->topics["delivery"] ??
                    $this->bot->topics["delivery"] ?? null;

            }

            $price = 0;

            $extraCharge = $partnerProductBox[$partner->bot_domain]["extra_charge"];
            $isWeightProduct = false;
            if (!is_null($product)) {

                $isWeightProduct = $product->is_weight_product ?? false;

                $count = $item->count;

                $currentPrice = $item->params["discount_price"] ?? $product->current_price;

                $price = (($currentPrice ?? 0) * (1 + $extraCharge / 100)) * $count;

                $unitOfMeasure = "ед.";

                if ($isWeightProduct) {
                    $weightConfig = (object)$product->weight_config ?? null;
                    $step = $weightConfig->step ?? 100;

                    $price = ((($currentPrice ?? 0) * (1 + $extraCharge / 100)) * $count) / $step;

                    $unitOfMeasure = "гр.";
                }

                $tmpMessage = is_null($comment) ?
                    sprintf("💎%s x%s $unitOfMeasure=%s руб.\n",
                        $product->title,
                        $item->count,
                        $price
                    ) :
                    sprintf("💎%s x%s $unitOfMeasure=%s руб.\n<em>(%s)</em>\n",
                        $product->title,
                        $item->count,
                        $price,
                        $comment
                    );

                $partnerProductBox[$partner->bot_domain]["message"] .= $tmpMessage;
                // $productMessage .= $tmpMessage;

                $tmpOrderProductInfo[] = (object)[
                    "title" => $product->title,
                    "count" => $item->count,
                    "price" => $price,
                    'frontpad_article' => $product->frontpad_article ?? null,
                    'iiko_article' => $product->iiko_article ?? null,
                ];


                if (!in_array($product->id, $ids)) {
                    $ids[] = $product->id;

                    $partnerProductBox[$partner->bot_domain]["products"][] = $tmpOrderProductInfo;
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

                    $price += ($product->current_price ?? 0) * (1 + $extraCharge / 100);


                    if (!in_array($product->id, $ids)) {
                        $ids[] = $product->id;

                        $partnerProductBox[$partner->bot_domain]["products"][] = $tmpOrderProductInfo;
                    }

                }

                $price = $price * $item->count;

                // $partnerProductBox[$partner->bot_domain]["summary_price"] += $price;
                //  $partnerProductBox[$partner->bot_domain]["summary_count"] += $item->count;

                $tmpMessage = sprintf("💎Коллекция `%s` x%s=%s руб.:\n%s\n",
                    ($collection->title),
                    $item->count,
                    $price,
                    $collectionTitles,
                );

                //$productMessage .= $tmpMessage;
                $partnerProductBox[$partner->bot_domain]["message"] .= $tmpMessage;

            }

            $partnerProductBox[$partner->bot_domain]["summary_count"] += $isWeightProduct ? 1 : $item->count;
            $partnerProductBox[$partner->bot_domain]["summary_price"] += $price;
            $partnerProductBox[$partner->bot_domain]["summary_discount"] += $item->params["discount_amount"] ?? 0;

            $summaryDiscount += $item->params["discount_amount"] ?? 0;
            $summaryCount += $isWeightProduct ? 1 : $item->count;
            $summaryPrice += $price;

            $item->ordered_at = env("APP_DEBUG") ? null : Carbon::now();
            $item->save();

        }

        $deliveryNote = $this->fsPrepareDeliveryNote();
        $cashback = $this->prepareCashbackDiscount($summaryPrice);
        $this->useCashBackForPayment($cashback ?? 0);

        //todo: $deliveryPrice для всех partnerBox и суммарная

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
            'summary_price' => $summaryPrice - $cashback,
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

        BusinessLogic::review()
            ->setBotUser($this->botUser)
            ->setBot($this->bot)
            ->prepareReviews($order->id, $ids);


        foreach ($partnerProductBox as $key => $box) {
            $box = (object)$partnerProductBox[$key];

            $botInBox = Bot::query()->find($box->id);

            $this->fsPrepareFrontPad($order, $tmpOrderProductInfo, $botInBox->id);

            $iiko = $botInBox->iiko ?? null;

            if ($iiko && !is_null($iiko->api_login ?? null)) {

                try {
                    BusinessLogic::iiko()
                        ->setBot($this->bot)
                        ->createOrder([
                            "guests_count" => $this->data["persons"] ?? 1,
                            "order_id" => $order->id,
                            "customer" => [
                                "name" => $this->data["name"],
                                "surname" => $this->botUser->fio_from_telegram ?? $this->botUser->telegram_chat_id ?? "",
                                "comment" => $deliveryNote,
                                "gender" => $this->botUser->sex ? "Male" : "Female",
                                "type" => "regular",
                                "phone" => $this->data["phone"],
                            ],
                            "items" => $basket,
                        ]);
                } catch (\Exception $exception) {
                    Log::info("error iiko =>" . $exception->getMessage());
                }
            }


        }

        $needBill = false;

        //todo: сделать ссылку в модели бота

        $linkUserId = $this->botUser->telegram_chat_id;

        $keyboard = [
            [
                ["text" => "✉Работа с заказом пользователя", "url" =>
                    "https://t.me/" . ($this->bot->bot_domain ?? '-') . "?start=" . base64_encode("003" . $linkUserId)
                ]
            ]
        ];


        ini_set('max_execution_time', 300);
        $summaryProductMessage = "<b>⚠️⚠️⚠️Сводный заказ⚠️⚠️⚠️</b>\n"
            . (!$needPickup ? "#заказдоставка\n" : "#заказсамовывоз\n");

        $recountDeliveryPrice = $deliveryPrice == 0;

        foreach ($partnerProductBox as $key => $box) {
            $box = (object)$partnerProductBox[$key];

            $resultMessage = "Заказ из <b>$box->title</b>\n";
            $resultMessage .= (!$needPickup ? "#заказдоставка\n" : "#заказсамовывоз\n");
            //  $resultMessage .= $this->checkWheelOfFortuneAction();
            $resultMessage .= $this->fsPrepareDisabilities();

            $resultMessage .= $box->message ?? 'Неуказанный продукт (ошибка)';

            $localSummaryCount = $partnerProductBox[$key]["summary_count"] ?? 0;
            $localSummaryPrice = $partnerProductBox[$key]["summary_price"] ?? 0;
            $localSummaryDiscount = $partnerProductBox[$key]["summary_discount"] ?? 0;


            $resultMessage .= $this->fsPrepareUserInfo($order, $cashback);

            if ($localSummaryDiscount > 0)
                $resultMessage .= "\nСкидка по товарам: <b>-$localSummaryDiscount руб.</b>";
            $resultMessage .= "\nИтого: <b>" . $localSummaryPrice . " руб.</b> за <b>$localSummaryCount ед.</b>\n";

            $summaryProductMessage .= "\n<b>﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌</b>\n" .
                "Заказ из <b>$box->title</b>\n"
                . ($partnerProductBox[$key]["message"] ?? '')
                . "\nСкидка по товарам: <b>-$localSummaryDiscount руб.</b>"
                . "\nИтого: <b>" . $localSummaryPrice . " руб.</b> за <b>$localSummaryCount ед.</b>";


            if ($box->delivery_price > 0) {
                $localDeliveryPrice = $box->delivery_price;
                $localDistance = $box->distance;
                $resultMessage .= "\nДоставка: <b>" . $localDeliveryPrice . " руб.</b> за $localDistance км";
                $resultMessage .= "\nИтого c доставкой: <b>" . ($localSummaryPrice + $localDeliveryPrice) . " руб.</b>";

                $summaryProductMessage .= "\nДоставка: <b>" . $localDeliveryPrice . " руб.</b> за $localDistance км";
                $summaryProductMessage .= "\nИтого c доставкой: <b>" . ($localSummaryPrice + $localDeliveryPrice) . " руб.</b>";

                if ($recountDeliveryPrice)
                    $deliveryPrice += $localDeliveryPrice;
            }


            BotMethods::bot()
                ->whereDomain($key)
                ->sendInlineKeyboard(
                    $box->order_channel ?? null,
                    $resultMessage . "\n",
                    $box->id == $this->bot->id ? $keyboard : [],
                    $box->thread
                );
            sleep(1);
        }

        $summaryProductMessage .= "\n<b>﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌﹌</b>\n";


        if ($useCashback)
            $summaryProductMessage .= "Использованы баллы: <b>-$cashback</b> руб.\n";
        $summaryProductMessage .= "Итоговая скидка: <b>-$summaryDiscount</b> руб.\n";
        $summaryProductMessage .= "Итого по всем: <b>" . ($summaryPrice - $cashback) . " руб.</b> за <b>$summaryCount ед.</b>\n";


        if (count($deliveryDetails) > 0) {

            if ($deliveryPrice > 0) {
                $summaryProductMessage .= "Доставка: <b>" . $deliveryPrice . " руб.</b> за $distance км\n";
                $summaryProductMessage .= "Итого c доставкой: <b>" . (($summaryPrice - $cashback) + $deliveryPrice) . " руб.</b>\n";
            } else
                $summaryProductMessage .= "Доставка: <b>рассчитывается курьером</b>\n";

        }


        $summaryProductMessage .= $this->fsPrepareUserInfo($order, $cashback);
        // $summaryProductMessage .= $this->checkWheelOfFortuneAction();
        $summaryProductMessage .= $this->fsPrepareDisabilities();
        $summaryProductMessage .= "\n\n<a href='tg://user?id=$linkUserId'>Перейти к чату с пользователем</a>\n";


        if ($this->bot->config["partners"]["is_active"] ?? false) {
            sleep(1);
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard(
                    $this->bot->order_channel ?? null,
                    $summaryProductMessage,
                    $keyboard,
                    $this->bot->topics["delivery"] ?? null
                );
        }

        $order->delivery_price = $deliveryPrice;
        $order->save();

        switch ($paymentType) {
            case 0:
                BusinessLogic::payment()
                    ->setBot($this->bot)
                    ->setBotUser($this->botUser)
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
                return BusinessLogic::payment()
                    ->setBot($this->bot)
                    ->setBotUser($this->botUser)
                    ->sbpForShop($order, $summaryProductMessage);


        }

        if ($needBill)
            $this->fsPrintPDFInfo(
                order: $order,
                summaryPrice: $summaryPrice,
                summaryCount: $summaryCount,
                tmpOrderProductInfo: $tmpOrderProductInfo,
                cashback: $cashback
            );


        $this->sendPaidReceiptToChannel($order, $summaryProductMessage);

        $paymentInfo = sprintf($this->bot->config["payment_info"] ?? "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>",
            $linkUserId);

        $summaryProductMessage .= "\n\n$paymentInfo";

        $this->fsSendResult($summaryProductMessage);

        $config = $this->botUser->config ?? [];
        $config["current_promocodes"] = [];
        $this->botUser->config = $config;
        $this->botUser->save();

        return null;

    }

    /**
     * @throws RequestException
     * @throws ValidationException
     */
    private function goodsShopCheckout()
    {
        $paymentType = $this->data["payment_type"] ?? 4;
        $cdek = json_decode($this->data["cdek"] ?? '{}');

        $address = $cdek->to->address ?? $cdek->to->office->location->address_full ?? null;
        if (is_null($address))
            throw new HttpException(400, "Не указан адрес пункта выдачи");

        $productMessage = "#заказдоставка\n";
        //     $productMessage .= $this->checkWheelOfFortuneAction();

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
            $comment = $item->comment ?? null;
            $product = $item->product ?? null;
            $collection = $item->collection ?? null;
            $price = 0;

            if (!is_null($product)) {
                $price = ($product->current_price ?? 0) * $item->count;
                $dimension = $product->dimension ?? (object)[];

                $productMessage .= sprintf(
                    "%s x%s=%s руб. (%s x %s x %s, %s грамм, <em>%s</em>)\n",
                    $product->title,
                    $item->count,
                    $price,
                    ($package->width ?? 0) == 0 ? $baseDimensions["width"] : $dimension->width,
                    ($package->height ?? 0) == 0 ? $baseDimensions["height"] : $dimension->height,
                    ($package->length ?? 0) == 0 ? $baseDimensions["length"] : $dimension->length,
                    (($package->weight ?? 0) == 0 ? $baseDimensions["weight"] : $dimension->weight) * 1000,
                    $comment ?? 'без комментария'
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

        $cashback = $this->prepareCashbackDiscount($summaryPrice);
        $this->useCashBackForPayment($cashback ?? 0);

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
            'summary_price' => $summaryPrice - $cashback,
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
            $url = (object)[
                "url" => null
            ];

            try {
                $url = BusinessLogic::payment()
                    ->setBot($this->bot)
                    ->setBotUser($this->botUser)
                    ->sbpForShop($order, $productMessage);
            } catch (\Exception $exception) {

            }


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

            return $url;
        }

        $productMessage .= $this->gsPrepareFromInfo($order, $cashback);
        // $tmpUserLink = "\n<a href='tg://user?id=$userId'>Перейти к чату с пользователем</a>\n";

        //$productMessage .= $tmpUserLink;
        //  $this->gsPrintPDFInfo($order, $summaryPrice, $summaryCount, $tmpOrderProductInfo, $discountItem->discount ?? 0);
        $this->gsSendResult($productMessage);
        $this->sendPaidReceiptToChannel($order, $productMessage);

        return null;
    }


    /**
     * @throws ValidationException
     * @throws RequestException
     */
    public function handler(): ?object
    {

        $displayType = $this->data["display_type"] ?? 0;

        switch ($displayType) {
            default:
            case 0:
                return $this->foodShopCheckout();
            case 1:
                return $this->goodsShopCheckout();

        }

    }
}
