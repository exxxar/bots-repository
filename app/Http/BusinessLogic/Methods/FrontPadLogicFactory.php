<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotMethods;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\FrontPadResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\FrontPad;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FrontPadLogicFactory extends BaseLogicFactory
{


    /**
     * @throws HttpException
     */
    public function getProducts()
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $frontPad = FrontPad::query()
            ->where("bot_id", $this->bot->id)
            ->first();

        if (is_null($frontPad) || is_null($frontPad->token ?? null))
            throw new HttpException(404, "FrontPad не подключен!");

        $result = Http::asForm()->post(config("frontpad.api_url") . "?get_products", [
            'secret' => trim($frontPad->token)
        ]);

        $status = $result->json("result") ?? "error";

        Log::info("LOADED FP PRODUCTS=>".print_r($result, true));

        if ($status == "error")
            throw new HttpException(403, "Ошибка получения списка товаров!");

        return $result->json();
    }

    public function getClient($clientPhone)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $frontPad = FrontPad::query()
            ->where("bot_id", $this->bot->id)
            ->first();

        if (is_null($frontPad) || is_null($frontPad->token ?? null))
            throw new HttpException(404, "FrontPad не подключен!");

        $result = Http::asForm()->post(config("frontpad.api_url") . "?get_client", [
            'secret' => $frontPad->token,
            'client_phone' => $clientPhone,
        ]);

        $status = $result->json("result") ?? "error";

        if ($status == "error")
            throw new HttpException(403, "Ошибка получения информации о клиенте!");

        return $result->json();
    }

    public function getCertificate($certificate)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $frontPad = FrontPad::query()
            ->where("bot_id", $this->bot->id)
            ->first();

        if (is_null($frontPad) || is_null($frontPad->token ?? null))
            throw new HttpException(404, "FrontPad не подключен!");

        $result = Http::asForm()->post(config("frontpad.api_url") . "?get_certificate", [
            'secret' => $frontPad->token,
            'certificate' => $certificate,
        ]);

        $status = $result->json("result") ?? "error";

        if ($status == "error")
            throw new HttpException(403, "Ошибка получения информации о сертификате!");

        return $result->json();
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function newOrder(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Требования функции не выполнены!");

        $validator = Validator::make($data, [
            "products" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        /*
         *
         *    "title" => $product->title,
                "count" => $tmpCount,
                "price" => $tmpPrice,
                'frontpad_article' => $product->frontpad_article ?? null,
                'iiko_article' => $product->iiko_article ?? null,
         */
        $products = array_values(Collection::make($data["products"])
            ->whereNotNull("frontpad_article")
            ->pluck("frontpad_article")->toArray());

        Log::info("products=>" . print_r($products, true));

        $productsKol = array_values(Collection::make($data["products"])
            ->whereNotNull("frontpad_article")
            ->pluck("count")->toArray());

        Log::info("products Kol=>" . print_r($productsKol, true));

        $frontPad = FrontPad::query()
            ->where("bot_id", $this->bot->id)
            ->first();

        if (is_null($frontPad) || is_null($frontPad->token ?? null))
            throw new HttpException(404, "FrontPad не подключен!");

        $hookUrl = env("app_url") . "/front-pad/callback/" . $this->bot->domain; //$data["hook_url"] ?? $frontPad->hook_url ?? null;//
        $channel = $data["channel"] ?? $frontPad->channel ?? null;//
        $affiliate = $data["affiliate"] ?? $frontPad->affiliate ?? null;//
        $point = $data["point"] ?? $frontPad->point ?? null;//

        $cash = $data["cash"] ?? false;

        if (!is_null($frontPad->pays ?? null))
            $payId = Collection::make($frontPad->pays)
                ->where("key", $cash ? "cash" : "card")
                ->first()->value ?? 1;

        if (!is_null($frontPad->statueses ?? null))
            $newOrder = Collection::make($frontPad->statueses)
                ->where("key", "new")
                ->first()->value ?? 1;


        $result = Http::asForm()->post(config("frontpad.api_url") . "?new_order", [
            'secret' => $frontPad->token,
            'product' => $products,//массив артикулов товаров [ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР];
            'product_kol' => $productsKol,//массив количества товаров [ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР];
            'product_mod' => $data["product_mod"] ?? null,//массив модификаторов товаров, где значение элемента массива является ключом родителя
            'product_price' => $data["product_price"] ?? null,
            'score' => $data["score"] ?? null, //баллы для оплаты заказа
            'sale' => $data["sale"] ?? null, //скидка, положительное, целое число от 1 до 100;
            'card' => $data["card"] ?? null, //карта клиента, положительное, целое число до 16 знаков;
            'street' => $data["street"] ?? null, //улица, длина до 50 знаков;
            'home' => $data["home"] ?? null, // дом, длина до 50 знаков;
            'pod' => $data["pod"] ?? null, //  подъезд, длина до 2 знаков;
            'et' => $data["et"] ?? null, //этаж, длина до 2 знаков;
            'apart' => $data["apart"] ?? null, //квартира, длина до 50 знаков;
            'phone' => $data["phone"] ?? null, //телефон, длина до 50 знаков;
            'mail' => $data["mail"] ?? null, //адрес электронной почты, длина до 50 знаков, доступно только с активной опцией автоматического сохранения клиентов;
            'descr' => $data["descr"] ?? null, //примечание, длина до 100 знаков;
            'name' => $data["name"] ?? null, //имя клиента, длина до 50 знаков;
            'pay' => $payId ?? 1, //отметка оплаты заказа, значение можно посмотреть в справочнике “Варианты оплаты”;
            'certificate' => $data["certificate"] ?? null, //номер сертификата;
            'person' => $data["person"] ?? 1, //оличество персон, длина 2 знака. Обратите внимание, привязка "автосписания" к количеству персон, переданному через api, не осуществляется;
            'tags' => $data["tags"] ?? null, //массив отметок заказов, значение кодов API можно посмотреть в справочнике программы.
            'hook_status' => [$newOrder ?? 1], //массив статусов заказов, значение кодов API можно посмотреть в справочнике программы.Передается в формате аналогичном массиву товаров (не более 5), см. пример;
            'hook_url' => $hookUrl,// url для отправки вебхука по текущему заказу (если параметр не передан, вебхук будетотправлен по url из настроек API;
            'channel' => $channel,// канал продаж, значение можно посмотреть в справочнике программы;
            'datetime' => $data["datetime"] ?? null,//время “предзаказа”, указывается в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС,
            //например 2016-08-15 15:30:00. Максимальный период предзаказа - 30 дней от текущей даты;
            'affiliate' => $affiliate, //филиал, значение можно посмотреть в справочнике программы;
            'point' => $point,//точка продаж, значение можно посмотреть в справочнике программы.


        ]);


        if ($result->json("result") == 'error') {
            if ($result->json("error") == "cash_close") {
                BotMethods::bot()
                    ->whereBot($this->bot)
                    ->sendMessage(
                        $this->botUser->telegram_chat_id,
                        "В данный момент наше заведение закрыто! Попробуйте оформить ваш заказ позже:)"
                    );
            }
        }
        Log::info("frontpad new order result " . print_r($result->json(), true));

        return $result->json();
    }


    /**
     * @throws ValidationException
     */
    public function store(array $data): FrontPadResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
           // "hook_url" => "required",
            "token" => "required",
        ]);


        if ($validator->fails())
            throw new ValidationException($validator);

        $pays = isset($data["pays"]) ? json_decode($data["pays"]) : null;
        $statuses = isset($data["statuses"]) ? json_decode($data["statuses"]) : null;

        $frontPad = FrontPad::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
                'hook_url' => $data["hook_url"] ?? null,
                'channel' => $data["channel"] ?? null,
                'affiliate' => $data["affiliate"] ?? null,
                'point' => $data["point"] ?? null,
                'token' => $data["token"] ?? null,
                'statuses' => $statuses,
                'pays' => $pays,
            ]);

        return new FrontPadResource($frontPad);
    }
}
