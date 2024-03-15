<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotManager;
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

class PaymentLogicFactory
{
    protected $bot;

    protected $botUser;

    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;
    }

    /**
     * @throws HttpException
     */
    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function checkout(array $data): void
    {
        Log::info("payment_data=>" . print_r($data, true));
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

     /*   Log::info("ids" . print_r($ids, true));*/


        $products = Product::query()
            ->whereIn("id", is_array($ids) ? $ids : [$ids])
            ->get();

      /*  Log::info("products" . print_r($products->toArray(), true));*/

        $prices = [];
        $currency = "RUB";
        $providerData = (object)[
            "receipt" => []
        ];

        $summaryPrice = 0;
        $summaryCount = 0;
        $tmpDescription = "";

        foreach ($products as $product) {

            $tmpCount = array_values(array_filter($tmpProducts, function ($item) use ($product) {
                return $item->id === $product->id;
            }))[0]->count ?? 0;

            $tmpPrice = ($product->current_price ?? 0) * $tmpCount;


            $prices[] = [
                "label" => $product->title,
                "amount" => $tmpPrice*100
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

/*        Log::info("prices " . print_r($prices, true));
        Log::info("price receipt " . print_r($providerData->receipt, true));
        Log::info("price after $summaryPrice");*/

        $payload = Str::uuid()->getBytes();

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

        Log::info("payload".print_r($payload, true));

        \App\Facades\BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInvoice(
                $this->botUser->telegram_chat_id,
                $title, $description, $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                $providerData
            );
    }
}
