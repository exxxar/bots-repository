<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Telegram\Bot\FileUpload\InputFile;

trait GoodsBasket
{

    private $from = null;

    private $tariffCode = null;

    private function gsPrepareFromInfo($order, $discount)
    {

        $cash = self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $address = (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));


        return sprintf( "🟢 Заказ №: %s\nИдентификатор: %s\nДанные для самовывоза:\nФ.И.О.: %s\nНомер телефона: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id,
                $this->data["name"] ?? 'Не указано',
                $this->data["phone"] ?? 'Не указано',
                $cash,
                $this->data["money"] ?? 'Не указано',
                $this->data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",

            );
    }

    private function gsSendResult($message)
    {
        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $paymentInfo = sprintf((Collection::make($this->slug->config)
            ->where("key", "payment_info")
            ->first())["value"] ?? "Спасибо за оформление заказа!",
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

    private function gsPrepareTariffInfo():string
    {
        if (is_null($this->data["cdek"]))
            return "";

        $cdek = json_decode($this->data["cdek"]);

        $tariffTitle = $cdek->tariff->tariff_name ?? '-';
        $deliveryPrice = $cdek->tariff->delivery_sum ?? 0;
        $min = $cdek->tariff->calendar_min ?? 0;
        $max = $cdek->tariff->calendar_max ?? 0;

        $cash = self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";

        return "Тариф:\n".
            "Название: $tariffTitle\n".
            "Цена доставки: $deliveryPrice руб.\n".
            "Период доставки: от $min до $max календарных дней\n".
            "Тип оплаты: $cash\n".
            "Использование баллов для оплаты: ".($useCashback?"да":"нет")."\n";
    }
    private function gsPrepareUserInfo(): string
    {

        if (is_null($this->data["cdek"]))
            return "";

        $cdek = json_decode($this->data["cdek"]);

        $officeTitle = $cdek->to->office->name ?? 'Склад доставки СДЭК';
        $officeAddress = $cdek->to->office->location->address_full ?? 'не указано';
        $longitude = $cdek->to->office->location->longitude ?? 0;
        $latitude = $cdek->to->office->location->latitude ?? 0;
        $workTime = $cdek->to->office->work_time ?? 'не указано';
        $note = $cdek->to->office->note ?? 'не указано';
        $site = $cdek->to->office->site ?? 'не указано';
        $email = $cdek->to->office->email ?? 'не указано';

        $tmpPhones = $cdek->to->office->phones ?? [];

        $phones = "";

        foreach ($tmpPhones as $phone)
            $phones .= ($phone->number ?? '-').", ";

        return "Адрес доставки:\n".
            "Название офиса: $officeTitle\n".
            "Адрес: $officeAddress (<code>$longitude, $latitude</code>)\n".
            "Рабочее время: $workTime\n".
            "Описание: $note\n".
            "Сайт: $site\n".
            "Почта: $email\n".
            "Телефон: $phones\n";
    }

    private function gsPrepareFromAddress()
    {

        $cdekSettings = !is_null($this->bot->cdek->config ?? null) ? (object)$this->bot->cdek->config ?? null : null;

        $this->tariffCode = $cdekSettings->tariff_code ?? null;

        $this->from = (object)[
            "region" => $cdekSettings->region,
            "city" => $cdekSettings->city,
            "office" => $cdekSettings->office,

        ];


        return (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));
    }

    private function gsPrintPDFInfo($order, $summaryPrice, $summaryCount, $tmpOrderProductInfo, $discount)
    {


        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        $address = $this->prepareAddress();

        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $paymentInfo = sprintf((Collection::make($this->slug->config)
            ->where("key", "payment_info")
            ->first())["value"] ?? "Спасибо за заказ!",
            $userId);

        $mpdf = new Mpdf();
        $current_date = Carbon::now("+3:00")->format("Y-m-d H:i:s");

        $number = Str::uuid();


        $mpdf->WriteHTML(view("pdf.order-cdek", [
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



}
