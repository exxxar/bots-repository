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
    private array $data;

    private Bot $bot;
    private BotUser $botUser;
    private BotMenuSlug $slug;

    private mixed $uploadedImage;

    private const PAYMENT_TYPES = ["Онлайн в боте", "Картой в заведении", "Переводом", "Наличными","СБП"];

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

    private function prepareDisabilities(): string
    {

        $hasDisability = ($this->data["has_disability"] ?? "false") == "true";

        $disabilities = json_decode($this->data["disabilities"] ?? '[]');
        $allergy = $this->data["allergy"] ?? 'не указана';

        $tmpMessage = "";
        if ($hasDisability) {

            $disabilitiesText = "<b>Внимание!</b> у клиента присутствуют ограничения по здоровью!\n";

            foreach ($disabilities as $disability)
                $disabilitiesText .= $disability == "пищевая аллергия" ? "-<em>$disability на: $allergy</em>\n" : "-<em>$disability</em>\n";

            $tmpMessage .= $disabilitiesText . "\n";


        }

        return $tmpMessage;
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
                "activate_price" => 0,
                "discount" => 0,
                "code" => "не указан"
            ];

        $discount = ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0) +
            ($summaryPrice >= ($promo->activate_price ?? 0) ? ($promo->discount ?? 0) : 0);

        return (object)[
            "discount" => $discount,
            "message" => ($discount > 0 ? "Скидка: $discount руб." : "") .
                (!is_null($promo->code ?? null) ? " скидка за промокод '$promo->code' составляет $promo->discount руб. (уже учтена)" : "")
        ];
    }

    private function prepareUserInfo($order, $discount)
    {


        $time = $this->data["time"] ?? null;
        $persons = $this->data["persons"] ?? 1;

        $cash = self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];
        $whenReady = ($this->data["when_ready"] ?? "false") == "true";
        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $address = (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));


        return !$needPickup ?
            sprintf(($whenReady ? "🟢" : "🟡") . "Заказ №: %s\nИдентификатор клиента: %s\nДанные для доставки:\nФ.И.О.: %s\nНомер телефона: %s\nАдрес: %s\nЦена доставки(тест): %s \nДистанция(тест): %s \nНомер подъезда: %s\nНомер этажа: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nДоставить ко времени:%s\nЧисло персон: %s\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id ?? '-',
                $this->data["name"] ?? 'Не указано',
                $this->data["phone"] ?? 'Не указано',
                $address . "," . ($this->data["flat_number"] ?? ""),
                $deliveryPrice ?? 0, //$distance
                $distance ?? 0, //$distance
                $this->data["entrance_number"] ?? 'Не указано',
                $this->data["floor_number"] ?? 'Не указано',
                $cash,
                $this->data["money"] ?? 'Не указано',
                $this->data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            ) :
            sprintf(($whenReady ? "🟢" : "🟡") . "Заказ №: %s\nИдентификатор: %s\nДанные для самовывоза:\nФ.И.О.: %s\nНомер телефона: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nЗаберу в:%s\nЧисло персон: %s\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id,
                $this->data["name"] ?? 'Не указано',
                $this->data["phone"] ?? 'Не указано',
                $cash,
                $this->data["money"] ?? 'Не указано',
                $this->data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            );
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

    private function sendResult($message)
    {
        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $paymentInfo = sprintf((Collection::make($this->slug->config)
            ->where("key", "payment_info")
            ->first())["value"] ?? "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>",
            $userId);

        $botDomain = $this->bot->bot_domain;
        $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $this->botUser->telegram_chat_id);

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
                "$message\n",
                $keyboard,
                $thread
            )
            ->sendInlineKeyboard(
                $this->botUser->telegram_chat_id,
                ("Спасибо, ваш заказ появился в нашей системе:\n\n<em>$message</em>\n\n$paymentInfo" ?? "Данные не найдены") .
                "\nВы можете оставить отзыв с фото и получить от нас дополнительный КэшБэк!",
                [
                    [
                        ["text" => "📢Написать отзыв с фото", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/" . $this->bot->bot_domain . "?slug=route&hide_menu#/s/feedback"
                        ]],
                    ],
                ]
            );
    }

    private function prepareAddress(): string
    {


        return (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));
    }

    private function printPDFInfo($order, $summaryPrice, $summaryCount, $tmpOrderProductInfo, $discount)
    {


        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        $address = $this->prepareAddress();

        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $paymentInfo = sprintf((Collection::make($this->slug->config)
            ->where("key", "payment_info")
            ->first())["value"] ?? "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>",
            $userId);

        $mpdf = new Mpdf();
        $current_date = Carbon::now("+3:00")->format("Y-m-d H:i:s");

        $number = Str::uuid();


        $mpdf->WriteHTML(view("pdf.order", [
            "title" => $this->bot->title ?? $this->bot->bot_domain ?? 'CashMan',
            "uniqNumber" => $number,
            "orderId" => $order->id,
            "name" => $order->receiver_name,
            "phone" => $order->receiver_phone,
            "address" => $address . "," . ($this->data["flat_number"] ?? ""),
            "message" => ($this->data["info"] ?? 'Не указано'),
            "entranceNumber" => ($this->data["entrance_number"] ?? 'Не указано'),
            "floorNumber" => ($this->data["floor_number"] ?? 'Не указано'),
            "cashType" => $cash,
            "money" => ($this->data["money"] ?? 'Не указано'),
            "disabilitiesText" => ($disabilitiesText ?? 'не указаны'),
            "totalPrice" => $summaryPrice,
            "discount" => $useCashback ? $discount : 0,
            "totalCount" => $summaryCount,
            "distance" => $distance ?? 0, //$distance
            "deliveryPrice" => $deliveryPrice ?? 0, //цена доставки
            "currentDate" => $current_date,
            "code" => "Без промокода",
            "promoCount" => "0",
            "paymentInfo" => $paymentInfo,
            "products" => $tmpOrderProductInfo,
            "info" => $this->data["info"] ?? 'Не указано',
        ]));

        $file = $mpdf->Output("order-$number.pdf", \Mpdf\Output\Destination::STRING_RETURN);


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument(
                $this->botUser->telegram_chat_id,
                "Информация о заказе #" . ($order->id ?? 'не указан'),
                InputFile::createFromContents($file, "invoice.pdf")
            );
    }

    private function prepareFrontPad($order, $tmpOrderProductInfo)
    {

        if (is_null($this->bot->frontPad ?? null))
            return;

        $persons = $this->data["persons"] ?? 1;
        $whenReady = ($this->data["when_ready"] ?? "false") == "true";
        $time = $this->data["time"] ?? null;
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        BusinessLogic::frontPad()
            ->setBot($this->bot)
            ->setBotUser($this->botUser)
            ->newOrder([
                "products" => $tmpOrderProductInfo,
                "phone" => $order->receiver_phone,
                "descr" => $this->data["info"] ?? 'Не указано',
                "name" => $order->receiver_name,
                "home" => ($this->data["building"] ?? ""),
                "street" => ($this->data["street"] ?? ""),
                'pod' => ($this->data["entrance_number"] ?? 'Не указано'),
                'et' => ($this->data["floor_number"] ?? 'Не указано'),
                'apart' => ($this->data["flat_number"] ?? ""),
                'person' => $persons,
                'datetime' => ($whenReady ? null
                    : Carbon::parse($time)->format('Y-m-d H:i:s')),
                'cash' => $cash
            ]);
    }

    private function prepareDeliveryNote(): string
    {


        $disabilitiesText = $this->prepareDisabilities();

        $persons = $this->data["persons"] ?? 1;
        $whenReady = ($this->data["when_ready"] ?? "false") == "true";
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        $time = $this->data["time"] ?? null;

        $this->botUser->city = $this->data["city"] ?? $this->botUser->city ?? null;
        $this->botUser->address = ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? "");
        $this->botUser->save();

        return ($this->data["info"] ?? 'Не указано') . "\n"
            . (is_null($this->data["entrance_number"] ?? null) ? "Номер подъезда: " . $this->data["entrance_number"] . "\n" : "")
            . (is_null($this->data["floor_number"] ?? null) ? "Номер этажа: " . $this->data["floor_number"] . "\n" : "")
            . "Тип оплаты: " . $cash . "\n"
            . (is_null($this->data["money"] ?? null) ? "Сдача с: " . $this->data["money"] . "\n" : "")
            . "Время доставки:" . ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')) . "\n"
            . "Число персон:" . $persons . "\n"
            . "Ограничения:\n" . ($disabilitiesText ?? 'не указаны');
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

    /**
     * @throws ValidationException
     */
    public function handler()
    {


        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $deliveryPrice = $this->data["delivery_price"] ?? 0;
        $paymentType = $this->data["payment_type"] ?? 4;

        $message = (!$needPickup ? "#заказдоставка\n\n" : "#заказсамовывоз\n\n");
        $message .= $this->checkWheelOfFortuneAction();
        $message .= $this->prepareDisabilities();

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
                $message .= sprintf("💎%s x%s=%s руб.\n",
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
                $message .= sprintf("💎Коллекция `%s` x%s=%s руб.:\n%s\n",
                    ($collection->title),
                    $item->count,
                    $price,
                    $collectionTitles,
                );


            }

            $summaryCount += $item->count;
            $summaryPrice += $price;
        }

        $deliveryNote = $this->prepareDeliveryNote();

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
            'address' => $this->prepareAddress() . "," . ($this->data["flat_number"] ?? ""),
            'receiver_latitude' => $geo->latitude ?? 0,
            'receiver_longitude' => $geo->longitude ?? 0,

            'status' => OrderStatusEnum::NewOrder->value,//новый заказ, взят доставщиком, доставлен, не доставлен, отменен
            'order_type' => OrderTypeEnum::InternalStore->value,//тип заказа: на продукт из магазина, на продукт конструктора
            'payed_at' => Carbon::now(),
        ]);

        $this->prepareFrontPad($order, $tmpOrderProductInfo);

        BusinessLogic::review()
            ->setBotUser($this->botUser)
            ->setBot($this->bot)
            ->prepareReviews($order->id, $ids);


        $message .= $discountItem->message ?? '';

        $message .= "Итого: $summaryPrice руб. за $summaryCount ед. \n\n";

        $message .= $this->prepareUserInfo($order, $discountItem->discount ?? 0);


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
                    ->sbp($order);
                break;

        }
        if ($needBill)
            $this->printPDFInfo(
                order: $order,
                summaryPrice: $summaryPrice,
                summaryCount: $summaryCount,
                tmpOrderProductInfo: $tmpOrderProductInfo,
                discount: $discountItem->discount
            );

        $this->sendResult($message);
        $this->sendPaidReceiptToChannel($order);
    }
}
