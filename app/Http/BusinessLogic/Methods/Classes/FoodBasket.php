<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Telegram\Bot\FileUpload\InputFile;

trait FoodBasket
{
    private function fsPrepareDisabilities(): string
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

    private function fsPrepareUserInfo($order, $discount)
    {


        $time = $this->data["time"] ?? null;
        $persons = $this->data["persons"] ?? 1;

        $cash = self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];
        $whenReady = ($this->data["when_ready"] ?? "false") == "true";
        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $address = (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));


        return !$needPickup ?
            sprintf("\n\n".($whenReady ? "🟢" : "🟡") . " Заказ №: %s\nИдентификатор клиента: %s\nДанные для доставки:\nФ.И.О.: %s\nНомер телефона: %s\nАдрес: %s\nЦена доставки: %s руб.\nДистанция: %s км\nНомер подъезда: %s\nНомер этажа: %s\nТип оплаты: <b>%s</b>\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nДоставить ко времени:%s\nЧисло персон: %s\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id ?? '-',
                $this->data["name"] ?? 'Не указано',
                $this->data["phone"] ?? 'Не указано',
                $address . "," . ($this->data["flat_number"] ?? ""),
                $order->delivery_price ?? 0,
                $order->delivery_range ?? 0,
                $this->data["entrance_number"] ?? 'Не указано',
                $this->data["floor_number"] ?? 'Не указано',
                $cash,
                $this->data["money"] ?? 'Не указано',
                $this->data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            ) :
            sprintf("\n\n".($whenReady ? "🟢" : "🟡") . "Заказ №: %s\nИдентификатор: %s\nДанные для самовывоза:\nФ.И.О.: %s\nНомер телефона: %s\nТип оплаты: <b>%s</b>\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nЗаберу в:%s\nЧисло персон: %s\n",
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

    private function fsSendResult($message)
    {

        sleep(1);
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->botUser->telegram_chat_id,
                ("Спасибо, ваш заказ появился в нашей системе:\n\n<em>$message</em>" ?? "Данные не найдены") .
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

    private function fsPrepareAddress(): string
    {


        return (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));
    }

    private function fsPrintPDFInfo($order, $summaryPrice, $summaryCount, $tmpOrderProductInfo, $discount)
    {


        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        $address = $this->fsPrepareAddress();

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


        sleep(1);
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument(
                $this->botUser->telegram_chat_id,
                "Информация о заказе #" . ($order->id ?? 'не указан'),
                InputFile::createFromContents($file, "invoice.pdf")
            );
    }

    private function fsPrepareFrontPad($order, $tmpOrderProductInfo)
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

    private function fsPrepareDeliveryNote(): string
    {


        $disabilitiesText = $this->fsPrepareDisabilities();

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
}
