<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\BusinessLogic\Methods\Classes\Tinkoff;
use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
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
    public function sbpTablePayment(array $data, $table): string
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Бот не найден!");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

        $isSelf = ($this->data["is_self"] ?? "false") == "true";

        $client = is_null($data["client"] ?? null) ? null : json_decode($data["client"]);

        if (is_null($client))
            throw new HttpException(400, "Не указаны данные клиента");

        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("table_id", $table->id)
            ->whereNull("ordered_at");

        if ($isSelf)
            $basket = $basket->where("bot_user_id", $this->botUser->id);

        $basket = $basket->get();


        $items = [];
        $tmpOrderProductInfo = [];

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

        foreach ($basket as $basketItem) {
            $product = $basketItem->product ?? null;
            $collection = $basketItem->collection ?? null;
            $count = $basketItem->count ?? 0;
            $price = 0;

            //todo: сделать 1 товар "обед в заведении\оплата за столик"

            if (!is_null($product)) {
                $price = $product->current_price ?? 0;//* $count;

                $description .= "$product->title x$count = $price,\n";

                $tmpOrderProductInfo[] = (object)[
                    "title" => $product->title,
                    "count" => $count,
                    "price" => $price,
                    'frontpad_article' => $product->frontpad_article ?? null,
                    'iiko_article' => $product->iiko_article ?? null,
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

                $description .= "Коллекция $collection->title x$count = $price,\n";

                $tmpOrderProductInfo[] = (object)[
                    "title" => "Коллекция `" . ($collection->title) . "`: " . $product->title,
                    "count" => 1,
                    "price" => $product->current_price ?? 0,
                    'frontpad_article' => $product->frontpad_article ?? null,
                    'iiko_article' => $product->iiko_article ?? null,
                ];

                $price = $price * $basketItem->count;
            }


            $summaryCount += $count;
            $summaryPrice += $price;
        }

        $additionalServices = $table->additional_services ?? [];
        foreach ($additionalServices as $serviceItem) {
            $serviceItem = (object)$serviceItem;

            $price = $serviceItem->price ?? 0;//* $count;

            $description .= "$serviceItem->title x1 = $price,\n";

            $tmpOrderProductInfo[] = (object)[
                "title" => $serviceItem->title,
                "count" => 1,
                "price" => $price,
                'frontpad_article' => null,
                'iiko_article' => null,
            ];

            $summaryCount += 1;
            $summaryPrice += $price;
        }


        $discount = ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0) +
            ($summaryPrice >= ($promo->activate_price ?? 0) ? ($promo->discount ?? 0) : 0);

        $priceWithDiscount = $summaryPrice - ($summaryPrice * $discount);
        $items[] = [
            'Name' => "Оплата столика",
            'Quantity' => 1,
            'Price' => $priceWithDiscount,    //цена товара в рублях
            'NDS' => $vat ?? 'vat20',  //НДС //tax
        ];

        $tinkoff = new Tinkoff(config('sbp.payments.tinkoff.url'), $terminalKey, $terminalPassword);

        $order = Order::query()->create([
            'bot_id' => $this->bot->id,
            'deliveryman_id' => null,
            'customer_id' => $this->botUser->id,
            'delivery_service_info' => null,//информация о сервисе доставки
            'deliveryman_info' => null,//информация о доставщике
            'product_details' => [
                (object)[
                    "data" => $data,
                    "from" => $this->bot->title ?? $this->bot->bot_domain ?? $this->bot->id,
                    "products" => $tmpOrderProductInfo
                ]
            ],//информация о продуктах и заведении, из которого сделан заказ
            'product_count' => $summaryCount,
            'summary_price' => $priceWithDiscount,
            'delivery_price' => 0,
            'delivery_range' => 0,
            'deliveryman_latitude' => 0,
            'deliveryman_longitude' => 0,
            'delivery_note' => "Обслуживание столика $table->number",
            'receiver_name' => $client->name ?? 'Нет имени',
            'receiver_phone' => $client->phone ?? 'Нет телефона',
            'address' => "",
            'table_id' => $table->id,
            'receiver_latitude' => 0,
            'receiver_longitude' => 0,
            'status' => OrderStatusEnum::NewOrder->value,//новый заказ, взят доставщиком, доставлен, не доставлен, отменен
            'order_type' => OrderTypeEnum::InternalStore->value,//тип заказа: на продукт из магазина, на продукт конструктора
            'payed_at' => null,
        ]);

        $payment = [
            'OrderId' => $order->id,        //Ваш идентификатор платежа
            'Amount' => $priceWithDiscount,           //сумма всего платежа в рублях
            'Language' => 'ru',            //язык - используется для локализации страницы оплаты
            'Description' => "Оплата за обслуживание столика $table->number",   //описание платежа
            'Email' => $this->botUser->email ?? '',//email покупателя
            'Phone' => $order->receiver_phone,   //телефон покупателя
            'Name' => $order->receiver_name, //Имя покупателя
            'Taxation' => $tax     //Налогооблажение
        ];

//Получение url для оплаты
        $paymentURL = $tinkoff->paymentURL($payment, $items);

        if (!$paymentURL)
            throw new HttpException(400, "Ошибка формирования платежной ссылки!");

        $payment_id = $tinkoff->payment_id ?? Str::uuid()->toString();

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_user_id' => $botUser->id,
            'bot_id' => $bot->id,
            'payload' => $payment_id,
            'currency' => $currency,
            'total_amount' => $summaryPrice,
            'status' => 0,
            'products_info' => (object)[
                "payment_id" => $payment_id,
                "payload" => $tmpDescription ?? null,
                "prices" => $items,
            ],
        ]);

        $paymentMessage = $isSelf ? "Оплата за себя" : "Оплата за столика";

        $keyboard = [
            [
                ["text" => "Автоматическая проверка СБП", "callback_data" => "/test_sbp_tinkoff_automatic $payment_id $slug->id"]
            ],
        ];


        $keyboard[] = $isSelf ? [
            ["text" => "Клиент оплатил за себя(ручное подтверждение)", "callback_data" => "/test_table_manual_payment $botUser->id 0"]
        ] : [
            ["text" => "Клиент оплатил за столик(ручное подтверждение)", "callback_data" => "/test_table_manual_payment $table->id 1"]
        ];

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $table->officiant->telegram_chat_id,
                "Вам необходимо подтвердить факт платежа клиента за столиком #$table->number ($paymentMessage). Сумма платежа $summaryPrice руб. Это можно сделать несколькими способами:",
                $keyboard
            );

        return $paymentURL;

    }

    public function invoiceLink(array $data, $needKeyboard = false)
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Бот не найден!");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;
        $currency = "RUB";

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

        $items[] = [
            'Name' => $data["description"],
            'Quantity' => 1,
            'Price' => $data["amount"],    //цена товара в рублях
            'NDS' => $vat ?? 'vat20',  //НДС //tax
        ];

        $tinkoff = new Tinkoff(config('sbp.payments.tinkoff.url'), $terminalKey, $terminalPassword);

        $payment = [
            'OrderId' => $data["order_id"] ?? Str::uuid(),        //Ваш идентификатор платежа
            'Amount' => $data["amount"],           //сумма всего платежа в рублях
            'Language' => 'ru',            //язык - используется для локализации страницы оплаты
            'Description' => $data["description"],   //описание платежа
            'Email' =>$data["email"] ?? '',//email покупателя
            'Phone' => $data["phone"] ?? '',   //телефон покупателя
            'Name' => $data["name"] ?? '', //Имя покупателя
            'Taxation' => $tax     //Налогооблажение
        ];


//Получение url для оплаты
        $paymentURL = $tinkoff->paymentURL($payment, $items);

        if (!$paymentURL)
            throw new HttpException(400, "Ошибка формирования платежной ссылки!");

        $payment_id = $tinkoff->payment_id ?? null;

        $keyboard = [
            [
                ["text" => "Проверить оплату СБП", "callback_data" => "/test_invoice_sbp_tinkoff_automatic $payment_id $slug->id"]
            ],
        ];

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $botUser->telegram_chat_id,
                "<code>$paymentURL</code> - нажмите чтобы скопировать\n\nВам необходимо подтвердить факт платежа клиента <code>".($data["phone"]??'')."</code>. Сумма платежа " . $data["amount"] . " руб.",
                $keyboard
            );
    }

    /**
     * @throws ValidationException
     */
    public function sbpForFood($order, $message = null): void
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Бот не найден!");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;
        $currency = "RUB";

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


        $items[] = [
            'Name' => "Товар магазина",
            'Quantity' => 1,
            'Price' => $order->summary_price,    //цена товара в рублях
            'NDS' => $vat ?? 'vat20',  //НДС //tax
        ];

        $tinkoff = new Tinkoff(config('sbp.payments.tinkoff.url'), $terminalKey, $terminalPassword);


        $payment = [
            'OrderId' => $order->id,        //Ваш идентификатор платежа
            'Amount' => $order->summary_price,           //сумма всего платежа в рублях
            'Language' => 'ru',            //язык - используется для локализации страницы оплаты
            'Description' => "Оплата заказа",   //описание платежа
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

        $payment_id = $tinkoff->payment_id ?? Str::uuid()->toString();

        // $payload = Str::uuid()->toString();

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_user_id' => $botUser->id,
            'bot_id' => $bot->id,
            'payload' => $payment_id,
            'currency' => $currency,
            'total_amount' => $order->summary_price,
            'status' => 0,
            'products_info' => (object)[
                "order_id" => $order->id,
                "payment_id" => $payment_id,
                "payload" => $payment ?? null,
            ],
        ]);


        $keyboard = [
            [
                ["text" => "💳Перейти к оплате", "url" => "$paymentURL"],
            ],

        ];

        \App\Facades\BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->botUser->telegram_chat_id,
                $message ?? "Оплатите заказ, для того чтоб мы приступили к его выполнению:)",
                $keyboard
            );

        $keyboard = [
            [
                ["text" => "Автоматическая проверка СБП", "callback_data" => "/test_foods_sbp_tinkoff_automatic $payment_id $order->id"]
            ],
            [
                ["text" => "Клиент оплатил", "callback_data" => "/test_foods_manual_payment $botUser->id $order->id"]
            ]
        ];


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $bot->order_channel,
                "Вам необходимо подтвердить факт платежа клиента. Сумма платежа $order->summary_price руб. Это можно сделать несколькими способами:",
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

        $paymentToken = (Collection::make($slug->config)
            ->where("key", "payment_token")
            ->first())["value"] ?? null;

        $providerToken = $paymentToken ?? $bot->payment_provider_token;

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
