<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\BusinessLogic\Methods\Classes\Tinkoff;
use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentLogicFactory extends BaseLogicFactory
{

    /**
     * @throws ValidationException
     */
    public function sbp($order): void
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Бот не найден!");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNull("ordered_at")
            ->get();
        $items = [];

        $currency = "RUB";

        $summaryPrice = 0;
        $summaryCount = 0;
        $description = "";

        $config = $slug->config ?? null;

        if (is_null($config))
            throw new HttpException(400, "Система не настроена!");

        $sbp = Collection::make($config)
            ->where("key", "sbp")
            ->first()["value"] ?? null;

        $terminalKey = $sbp["tinkoff"]["terminal_key"] ?? null;
        $terminalPassword = $sbp["tinkoff"]["terminal_password"] ?? null;
        $tax = $sbp["tinkoff"]["tax"] ?? "osn";
        $vat = $sbp["tinkoff"]["vat"] ?? "vat20";


        foreach ($basket as $basketItem) {
            $product = $basketItem->product ?? null;
            $collection = $basketItem->collection ?? null;
            $count = $basketItem->count ?? 0;
            $price = 0;

            if (!is_null($product)) {
                $price = $product->current_price ?? 0 ;//* $count;

                $description .= "$product->title x$count = $price\n";

                $items[] = [
                    'Name' => $product->title,
                    'Quantity' => $count,
                    'Price' => $price,    //цена товара в рублях
                    'NDS' => $vat ?? 'vat20',  //НДС //tax
                ];

                $price = $price * $count;
            }

            if (!is_null($collection)) {
                $collectionTitles = "";


                $params = is_null($item->params ?? null) ? null : (object)$basketItem->params;


                foreach (($collection->products ?? []) as $basketProduct) {


                    if (!in_array($basketProduct->id, $params->ids ?? []))
                        continue;

                    $collectionTitles .= "-" . $basketProduct->title . "\n";
                    $price += $product->current_price ?? 0;
                }

                $description .= "Коллекция $collection->title x$count = $price\n";

                $items[] = [
                    'Name' => "Коллекция `" . ($collection->title) . "`: " . $collectionTitles,
                    'Quantity' => $count,
                    'Price' =>$price,    //цена товара в рублях
                    'NDS' => $vat ?? 'vat20',  //НДС //tax
                ];

                $price = $price * $basketItem->count;
            }


            $summaryCount += $count;
            $summaryPrice += $price;
        }

        $tinkoff = new Tinkoff(config('sbp.payments.tinkoff.url'), $terminalKey, $terminalPassword);


        $payment = [
            'OrderId' => $order->id,        //Ваш идентификатор платежа
            'Amount' => $summaryPrice,           //сумма всего платежа в рублях
            'Language' => 'ru',            //язык - используется для локализации страницы оплаты
            'Description' => $description,   //описание платежа
            'Email' => $this->botUser->email ?? '',//email покупателя
            'Phone' => $order->receiver_phone,   //телефон покупателя
            'Name' => $order->receiver_name, //Имя покупателя
            'Taxation' => $tax     //Налогооблажение
        ];



//Получение url для оплаты
        $paymentURL = $tinkoff->paymentURL($payment, $items);

        if (!$paymentURL) {
            \App\Facades\BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $this->botUser->telegram_chat_id, "Ошибка формирования платежной ссылки!");

            Log::info($tinkoff->error);
            return;

        }

        $payment_id = $tinkoff->payment_id;

        $payload = Str::uuid()->toString();

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_user_id' => $botUser->id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $summaryPrice,
            'status' => 0,
            'products_info' => (object)[
                "payment_id" => $payment_id,
                "payload" => $tmpDescription ?? null,
                "prices" => $items,
            ],
        ]);


        $keyboard = [
            [
                ["text" => "Перейти к оплате", "url" => "$paymentURL"],
            ],

        ];

        \App\Facades\BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->botUser->telegram_chat_id,
                "Оплатите заказ, для того чтоб мы приступили к его выполнению:)",
                $keyboard
            );

    }

    /**
     * @throws ValidationException
     */
    public function checkout(): void
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Бот не найден!");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

        $taxSystemCode = (Collection::make($slug->config)
            ->where("key", "tax_system_code")
            ->first())["value"] ?? $bot->company->vat_code ?? 1;


        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNull("ordered_at")
            ->get();
        $prices = [];

        $currency = "RUB";
        $providerData = (object)[
            "receipt" => []
        ];

        $summaryPrice = 0;
        $summaryCount = 0;
        $description = "";


        foreach ($basket as $basketItem) {


            $product = $basketItem->product ?? null;
            $collection = $basketItem->collection ?? null;
            $count = $basketItem->count ?? 0;
            $price = 0;

            if (!is_null($product)) {
                $price = ($product->current_price ?? 0) * $count;


                $prices[] = [
                    "label" => $product->title,
                    "amount" => $price * 100
                ];

                $description .= "$product->title x$count = $price\n";

                $providerData->receipt[] =
                    (object)[
                        "description" => "$product->title",
                        "quantity" => "$count.00",
                        "amount" => (object)[
                            "value" => $price * 100,
                            "currency" => $currency
                        ],
                        "vat_code" => $taxSystemCode
                    ];
            }

            if (!is_null($collection)) {
                $collectionTitles = "";


                $params = is_null($item->params ?? null) ? null : (object)$basketItem->params;


                foreach (($collection->products ?? []) as $basketProduct) {


                    if (!in_array($basketProduct->id, $params->ids ?? []))
                        continue;

                    $collectionTitles .= "-" . $basketProduct->title . "\n";
                    $price += $product->current_price ?? 0;
                }

                $price = $price * $basketItem->count;

                $prices[] = [
                    "label" => "Коллекция `" . ($collection->title) . "`: " . $collectionTitles,
                    "amount" => $price * 100
                ];

                $description .= "Коллекция $collection->title x$count = $price\n";

                $providerData->receipt[] =
                    (object)[
                        "description" => "Коллекция `" . ($collection->title) . "`: " . $collectionTitles,
                        "quantity" => "$count.00",
                        "amount" => (object)[
                            "value" => $price * 100,
                            "currency" => $currency
                        ],
                        "vat_code" => $taxSystemCode
                    ];


            }


            $summaryCount += $count;
            $summaryPrice += $price;
        }


        if ($summaryPrice < 100) {

            \App\Facades\BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $this->botUser->telegram_chat_id, "❗Сумма заказа должна быть больше чем <strong>100 руб 00 коп.</strong>❗");

            return;
        }

        $payload = Str::uuid()->toString();

        $providerToken = $bot->payment_provider_token;

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_user_id' => $botUser->id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $summaryPrice,
            'status' => 0,
            'products_info' => (object)[
                "payload" => $tmpDescription ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => (Collection::make($slug->config)
                    ->where("key", "need_name")
                    ->first())["value"] ?? false,
            "need_phone_number" => (Collection::make($slug->config)
                    ->where("key", "need_phone_number")
                    ->first())["value"] ?? false,
            "need_email" => (Collection::make($slug->config)
                    ->where("key", "need_email")
                    ->first())["value"] ?? false,
            "need_shipping_address" => (Collection::make($slug->config)
                    ->where("key", "need_shipping_address")
                    ->first())["value"] ?? false,
            "send_phone_number_to_provider" => (Collection::make($slug->config)
                    ->where("key", "need_send_phone_number_to_provider")
                    ->first())["value"] ?? false,
            "send_email_to_provider" => (Collection::make($slug->config)
                    ->where("key", "need_send_email_to_provider")
                    ->first())["value"] ?? false,
            "is_flexible" => (Collection::make($slug->config)
                    ->where("key", "is_flexible")
                    ->first())["value"] ?? false,
            "disable_notification" => (Collection::make($slug->config)
                    ->where("key", "disable_notification")
                    ->first())["value"] ?? false,
            "protect_content" => (Collection::make($slug->config)
                    ->where("key", "protect_content")
                    ->first())["value"] ?? false,
        ];


        $btnPaymentText = (Collection::make($slug->config)
            ->where("key", "btn_payment_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Оплатить";

        $keyboard = [
            [
                ["text" => $btnPaymentText, "pay" => true],
            ],

        ];

        $title = (Collection::make($slug->config)
            ->where("key", "checkout_title")
            ->first())["value"] ?? "Заказ товара";

        $description = (Collection::make($slug->config)
            ->where("key", "checkout_description")
            ->first())["value"] ?? "Ваш товар";


        \App\Facades\BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInvoice(
                $this->botUser->telegram_chat_id,
                title: $title,
                description: $description,
                prices: $prices,
                payload: $payload,
                providerToken: $providerToken,
                currency: $currency,
                needs: $needs,
                keyboard: $keyboard,
                providerData: $providerData
            );
    }

    /**
     * @throws ValidationException
     */
    public function checkoutLink(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "products.*" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

        //  Log::info("slug config".print_r($slug->config, true));

        $taxSystemCode = (Collection::make($slug->config)
            ->where("key", "tax_system_code")
            ->first())["value"] ?? $bot->company->vat_code ?? 1;

        $tmpProducts = $data["products"];
        $ids = Collection::make($tmpProducts)
            ->pluck("id")
            ->toArray();


        $products = Product::query()
            ->whereIn("id", is_array($ids) ? $ids : [$ids])
            ->get();


        $prices = [];
        $currency = "RUB";
        $providerData = (object)[
            "receipt" => []
        ];

        $summaryPrice = 0;
        $summaryCount = 0;
        $discount = $data["discount"] ?? 0;
        $tmpDescription = "";

        foreach ($products as $product) {

            $tmpCount = array_values(array_filter($tmpProducts, function ($item) use ($product) {
                return $item->id === $product->id;
            }))[0]->count ?? 0;

            $tmpPrice = ($product->current_price ?? 0) * $tmpCount;


            $prices[] = [
                "label" => $product->title,
                "amount" => $tmpPrice * 100
            ];

            $tmpDescription .= "$product->title x$tmpCount = $tmpPrice\n";

            $providerData->receipt[] =
                (object)[
                    "description" => "Заказ товара",
                    "quantity" => "$tmpCount.00",
                    "amount" => (object)[
                        "value" => $tmpPrice * 100,
                        "currency" => $currency
                    ],
                    "vat_code" => $taxSystemCode
                ];

            $summaryCount += $tmpCount;
            $summaryPrice += $tmpPrice;
        }


        $payload = Str::uuid()->toString();

        $providerToken = $bot->payment_provider_token;

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_user_id' => $botUser->id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => max(1, $summaryPrice - $discount),
            'status' => 0,
            'products_info' => (object)[
                "payload" => $tmpDescription ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => (Collection::make($slug->config)
                    ->where("key", "need_name")
                    ->first())["value"] ?? false,
            "need_phone_number" => (Collection::make($slug->config)
                    ->where("key", "need_phone_number")
                    ->first())["value"] ?? false,
            "need_email" => (Collection::make($slug->config)
                    ->where("key", "need_email")
                    ->first())["value"] ?? false,
            "need_shipping_address" => (Collection::make($slug->config)
                    ->where("key", "need_shipping_address")
                    ->first())["value"] ?? false,
            "send_phone_number_to_provider" => (Collection::make($slug->config)
                    ->where("key", "need_send_phone_number_to_provider")
                    ->first())["value"] ?? false,
            "send_email_to_provider" => (Collection::make($slug->config)
                    ->where("key", "need_send_email_to_provider")
                    ->first())["value"] ?? false,
            "is_flexible" => (Collection::make($slug->config)
                    ->where("key", "is_flexible")
                    ->first())["value"] ?? false,
            "disable_notification" => (Collection::make($slug->config)
                    ->where("key", "disable_notification")
                    ->first())["value"] ?? false,
            "protect_content" => (Collection::make($slug->config)
                    ->where("key", "protect_content")
                    ->first())["value"] ?? false,
        ];


        $title = (Collection::make($slug->config)
            ->where("key", "checkout_title")
            ->first())["value"] ?? "Заказ товара";

        $description = (Collection::make($slug->config)
            ->where("key", "checkout_description")
            ->first())["value"] ?? "Ваш товар";


        return \App\Facades\BotMethods::bot()
            ->whereBot($this->bot)
            ->createInvoiceLink(
                $this->botUser->telegram_chat_id,
                title: $title,
                description: $description,
                prices: $prices,
                payload: $payload,
                providerToken: $providerToken,
                currency: $currency,
                needs: $needs,
                providerData: $providerData
            );
    }
}
