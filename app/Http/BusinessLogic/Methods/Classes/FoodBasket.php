<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Models\Bot;
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

    private function fsPrepareUserInfo($order, $cashback = 0)
    {


        $time = $this->data["time"] ?? null;
        $persons = $this->data["persons"] ?? 1;

        $cash = self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];
        $whenReady = ($this->data["when_ready"] ?? "false") == "true";
        $needPickup = ($this->data["need_pickup"] ?? "false") == "true";
        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $address = (($this->data["city"] ?? "") . "," . ($this->data["street"] ?? "") . "," . ($this->data["building"] ?? ""));


        return !$needPickup ?
            sprintf("\n".($whenReady ? "🟢" : "🟡") . " Заказ №: <b>%s</b>\nИдентификатор клиента: <b>%s</b>\n\n<b>Данные для доставки:</b>\nФ.И.О.: <b>%s</b>\nНомер телефона: <b>%s</b>\nАдрес: <b>%s</b>\nЦена доставки: %s руб.\nДистанция: %s км\nНомер подъезда: %s\nНомер этажа: %s\nТип оплаты: <b>%s</b>\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nДоставить ко времени:%s\nЧисло персон: <b>%s</b> чел.\n",
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
                $useCashback ? $cashback : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            ) :
            sprintf("\n".($whenReady ? "🟢" : "🟡") . "Заказ №: <b>%s</b>\nИдентификатор: <b>%s</b>\n\n<b>Данные для самовывоза:</b>\nФ.И.О.: <b>%s</b>\nНомер телефона: <b>%s</b>\nТип оплаты: <b>%s</b>\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nЗаберу в:%s\nЧисло персон: <b>%s</b> чел.\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id,
                $this->data["name"] ?? 'Не указано',
                $this->data["phone"] ?? 'Не указано',
                $cash,
                $this->data["money"] ?? 'Не указано',
                $this->data["info"] ?? 'Не указано',
                $useCashback ? $cashback : "нет",
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

    private function ensureCityPrefix(string $address): string {
        // Список признаков города / населённого пункта
        $patterns = [
            '/\bг\.\b/ui',        // г.
            '/\bгород\b/ui',      // город
            '/\bс\.\b/ui',        // с.
            '/\bсело\b/ui',       // село
            '/\bпос\.\b/ui',      // пос.
            '/\bпос[её]лок\b/ui', // поселок / посёлок
            '/\bпгт\b/ui',        // пгт
        ];

        // Проверка наличия признака
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $address)) {
                return trim($address);
            }
        }

        // Если признака нет — добавляем "г."
        return 'г. ' . trim($address);
    }

    private function ensureStreetPrefix(string $street): string {
        // Список признаков улицы
        $patterns = [
            '/\bул\.\b/ui',          // ул.
            '/\bулица\b/ui',         // улица
            '/\bпр-т\b/ui',          // пр-т
            '/\bпросп\.\b/ui',       // просп.
            '/\bпроспект\b/ui',      // проспект
            '/\bпер\.\b/ui',         // пер.
            '/\bпереулок\b/ui',      // переулок
            '/\bбул\.\b/ui',         // бул.
            '/\bбульвар\b/ui',       // бульвар
            '/\bпроезд\b/ui',        // проезд
            '/\bш\.\b/ui',           // ш.
            '/\bшоссе\b/ui',         // шоссе
            '/\bнаб\.\b/ui',         // наб.
            '/\bнабережная\b/ui',    // набережная
            '/\bпл\.\b/ui',          // пл.
            '/\bплощадь\b/ui',       // площадь
            '/\bтракт\b/ui',         // тракт
            '/\bтуп\.\b/ui',         // туп.
            '/\bтупик\b/ui',         // тупик
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $street)) {
                return trim($street);
            }
        }

        return 'ул. ' . trim($street);
    }


    private function fsPrepareAddress(): string
    {

        $city = $this->ensureCityPrefix($this->data["city"] ?? "");
        $street = $this->ensureStreetPrefix($this->data["street"] ?? "");

        return "$city, $street, " . ($this->data["building"] ?? "");
    }

    private function fsPrintPDFInfo($order, $summaryPrice, $summaryCount, $tmpOrderProductInfo, $cashback = 0)
    {


        $useCashback = ($this->data["use_cashback"] ?? "false") == "true";
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        $address = $this->fsPrepareAddress();

        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $paymentInfo = sprintf($this->bot->config["payment_info"] ??
            "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>",
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
            "discount" => $useCashback ? $cashback : 0,
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

    private function fsPrepareFrontPad($order, $tmpOrderProductInfo, $partnerId = null)
    {
        $bot =   is_null($partnerId) ? $this->bot : Bot::query()->find($partnerId);
        $frontPad = $bot->frontPad ?? null;

        if (is_null($frontPad))
            return;

        $persons = $this->data["persons"] ?? 1;
        $whenReady = ($this->data["when_ready"] ?? "false") == "true";
        $time = $this->data["time"] ?? null;
        $cash =  self::PAYMENT_TYPES[$this->data["payment_type"] ?? 0];

        BusinessLogic::frontPad()
            ->setBot($bot)
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
