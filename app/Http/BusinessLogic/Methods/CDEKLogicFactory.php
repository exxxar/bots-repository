<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\CdekResource;
use App\Models\AmoCrm;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\Cdek;
use App\Models\Company;
use CdekSDK2\BaseTypes\Location;
use CdekSDK2\BaseTypes\Package;
use CdekSDK2\BaseTypes\Tariff;
use CdekSDK2\BaseTypes\Tarifflist;
use CdekSDK2\Constraints\Currencies;
use CdekSDK2\Exceptions\AuthException;
use CdekSDK2\Exceptions\RequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use Symfony\Component\HttpKernel\Exception\HttpException;
use CdekSDK2\BaseTypes;

class CDEKLogicFactory extends BaseLogicFactory
{
    use LogicUtilities;

    private $authUrl = 'https://api.cdek.ru';
    private $token;

    protected function auth()
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        if (is_null($this->bot->cdek ?? null))
            throw new HttpException(404, "СДЭК-параметры не заданы!");

        $account = $this->bot->cdek->account ?? null;
        $secure = $this->bot->cdek->secure_password ?? null;

        $response = Http::asForm()->post($this->authUrl . '/v2/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $account,
            'client_secret' => $secure,
        ]);

        $data = $response->json();
        $this->token = $data['access_token'] ?? null;
        return $this->token;
    }

    private function ensureToken()
    {
        if (!$this->token) {
            $this->auth();
        }
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    public function orderInfo(string $uuid): ?\CdekSDK2\Dto\Response
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $cdek = $this->auth();

        $result = $cdek
            ->orders()
            ->get($uuid);

        return $result->isOk() ?
            $cdek->formatResponse($result, \CdekSDK2\Dto\OrderInfo::class) :
            null;
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    public function removeOrder(string $uuid): ?\CdekSDK2\Dto\Response
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $cdek = $this->auth();

        $result = $cdek
            ->orders()
            ->delete($uuid);

        return $result->isOk() ?
            $cdek->formatResponse($result, \CdekSDK2\Dto\OrderInfo::class) :
            null;
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    protected function officeList($cityCode, $countryCode = 'ru'): mixed
    {

        $this->ensureToken();
        $query = $cityCode ? ['city_code' => $cityCode, 'country_code' => $countryCode] : [];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->authUrl . '/v2/deliverypoints', $query);

        return $response->json() ?? [];

    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    protected function cities($countryCode = null, $regionCode = null, $city = null): mixed
    {

        $filter = [];

        if (!is_null($countryCode))
            $filter['country_code'] = $countryCode;

        if (!is_null($regionCode))
            $filter['region_code'] = $regionCode;

        if (!is_null($city))
            $filter['city'] = $city;


        $this->ensureToken();
        $query = $regionCode ? ['region_code' => $regionCode, ...$filter] : [...$filter];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->authUrl . '/v2/location/cities', $query);

        return $response->json();
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    protected function regions(): mixed
    {

        $this->ensureToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->authUrl . '/v2/location/regions', ['country_codes' => "ru", 'page' => 0, 'size' => 1000]);

        return $response->json();

    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    public function calcByTariff(array $data): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "from" => "required",
            "to" => "required",
            "packages" => "required",
            "packages.*.weight" => "required|number",
            "packages.*.length" => "required|number",
            "packages.*.height" => "required|number",
            "packages.*.width" => "required|number",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $from = $this->getLocation(json_decode($data["from"]));
        $to = $this->getLocation(json_decode($data["to"]));

        $baseDimensions = $this->bot->cdek->config->base_dimensions ?? [
            "height" => 15,
            "width" => 15,
            "length" => 15,
            "weight" => 1,
        ];

        $packages = [];

        foreach (json_decode($data["packages"]) as $item) {
            $item = (object)$item;
            $packages[] = [
                'number' => $item->id,
                'weight' => (($item->weight ?? 0) == 0 ? $baseDimensions["weight"] : $item->weight) * 1000,
                'length' => ($item->length ?? 0) == 0 ? $baseDimensions["length"] : $item->length,
                'width' => ($item->width ?? 0) == 0 ? $baseDimensions["width"] : $item->width,
                'height' => ($item->height ?? 0) == 0 ? $baseDimensions["height"] : $item->height,
            ];
        }

        $this->ensureToken();

        $data = [
            "type" => 1,
            "date" => (new \DateTime())->format(\DateTime::ISO8601),
            "currency" => 1,
            "lang" => "rus",
            "from_location" => $from,
            "to_location" => $to,
            "packages" => $packages,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->authUrl.'/v2/calculator/tarifflist', $data);

        return $response->json();
    }


    /**
     * @throws RequestException
     * @throws ValidationException
     * @throws HttpException
     */
    public function calcBasketTariff(array $data): mixed
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции указаны!");

        $validator = Validator::make($data, [
            "to" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $cdek = $this->auth();

        $cdekSettings = !is_null($this->bot->cdek->config ?? null) ? (object)$this->bot->cdek->config ?? null : null;

        $tariffCode = $cdekSettings->tariff_code ?? null;

        $tmpFrom = (object)($cdekSettings->office["location"]);
        $from = [
            "country_code" => "RU",
            "code" => $cdekSettings->city["code"],
            "address" => $tmpFrom->address,
        ];

        if (!isset($data["to"]))
            throw new HttpException(404, "Не указан адрес назначения!");

        $tmpTo = (object)($data["to"]);

        $to = [
            "country_code" => "RU",
            "code" => $tmpTo->city["code"] ?? null,
            "address" => $tmpTo->office["location"]["address"] ?? null,
        ];

        $packages = [];

        $basket = Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNull("ordered_at")
            ->get();

        $baseDimensions = $this->bot->cdek->config->base_dimensions ?? [
            "height" => 15,
            "width" => 15,
            "length" => 15,
            "weight" => 1,
        ];

        foreach ($basket as $item) {
            $item = $item->product;

            $dimension = !is_null($item->dimension ?? null) ?
                (object)$item->dimension ?? null : null;

            $packages[] = [
                'number' => $item->id,
                'weight' => (($dimension->weight ?? 0) == 0 ? $baseDimensions["weight"] : $dimension->weight) * 1000,
                'length' => ($dimension->length ?? 0) == 0 ? $baseDimensions["length"] : $dimension->length,
                'width' => ($dimension->width ?? 0) == 0 ? $baseDimensions["width"] : $dimension->width,
                'height' => ($dimension->height ?? 0) == 0 ? $baseDimensions["height"] : $dimension->height,
            ];
        }


        $data = [
            "type" => 1,
            "tariff_code" => $tariffCode,
            "date" => (new \DateTime())->format(\DateTime::ISO8601),
            "currency" => 1,
            "lang" => "rus",
            "from_location" =>$from,
            "to_location" =>$to,
            "packages" => $packages,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->authUrl."/v2/calculator/tariff", $data);

        return $response->json();
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    public function calcByTariffCode($tariffCode, array $data): ?\CdekSDK2\Dto\Tariff
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "from" => "required",
            "to" => "required",
            "packages" => "required",
            "packages.*.weight" => "required|number",
            "packages.*.length" => "required|number",
            "packages.*.height" => "required|number",
            "packages.*.width" => "required|number",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $cdek = $this->auth();


        $from = $this->getLocation(json_decode($data["from"]));
        $to = $this->getLocation(json_decode($data["to"]));

        $packages = [];

        foreach ($data["packages"] as $item) {
            $item = (object)$item;
            $packages[] = Package::create([
                'weight' => $item->weight * 1000,
                'length' => $item->length,
                'width' => $item->width,
                'height' => $item->height,
            ]);
        }

        $tariff = Tariff::create([]);
        $tariff->date = (new \DateTime())->format(\DateTime::ISO8601);
        $tariff->type = Tarifflist::TYPE_ECOMMERCE;
        $tariff->currecy = Currencies::RUBLE;
        $tariff->lang = Tarifflist::LANG_RUS;
//Номера тарифов есть в документации к API: https://api-docs.cdek.ru/63345430.html
        $tariff->tariff_code = $tariffCode;
        $tariff->from_location = Location::create($from);
        $tariff->to_location = Location::create($to);
        $tariff->packages = $packages;

        $result = $cdek->calculator()
            ->add($tariff);

        return $result->isOk() ?
            $cdek->formatBaseResponse($result, \CdekSDK2\Dto\Tariff::class) : null;

    }


    /**
     * @throws RequestException
     * @throws HttpException
     * @throws ValidationException
     * @throws \Exception
     */
    public function createOrder(array $data, $orderId = null)
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "tariff" => "required",
            "sender_name" => "required",
            "recipient_name" => "required",
            "recipient_phones" => "required",
            "from" => "required",
            "to" => "required",
            "packages" => "required",
        ]);


        if (!$this->token) {
            $this->auth();
        }

        $type = 1;//($data["is_shop_mode"] ?? false) == "true" ? 1 : 2;
        $tariff = $data["tariff"];
        Log::info("tariff=>".print_r($tariff, true));
        $from = $data["from"];
        $to = $data["to"];
        $packages = $data["packages"];

        if ($validator->fails())
            throw new ValidationException($validator);

        $s_phones = [];

        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();


        foreach (($company->phones ?? []) as $item) {
            $s_phones[] = ['number' => '+'.preg_replace('/\D+/', '', $item)];
        }

        $r_phones = [];

        foreach (($data["recipient_phones"] ?? []) as $item) {
            $r_phones[] = ['number' => '+'.preg_replace('/\D+/', '', $item)];
        }

        $cdekSettings = !is_null($this->bot->cdek->config ?? null) ? (object)$this->bot->cdek->config ?? null : null;

        $tariffCode = $cdekSettings->tariff_code ?? null;

        $baseDimensions = $cdekSettings->base_dimensions ?? [
            "height" => 15,
            "width" => 15,
            "length" => 15,
            "weight" => 1,
        ];


        $tmpPackages = [];


        $index = 1;
        foreach ($packages as $package) {
            $package = (object)$package;

            $weight = $package->weight == 0 ? 0 : $package->weight;
            $packageItems = [];

            if (count($package->items ?? []) > 0)
                foreach ($package->items as $packageItem) {
                    $packageItem = (object)$packageItem;
                    $packageItems[] = [
                        'name' => $packageItem->name ?? 'Товар', //описание товара
                        'ware_key' => $packageItem->ware_key ?? "Товар".($package->id ?? $index), //артикул товара
                        'payment' => ['value' => $packageItem->payment ?? 0],
                        'cost' => $packageItem->price ?? 0, //объявленная стоимость (ценность)
                        'weight' => (($packageItem->weight ?? 0) ==0 ? 1 : $packageItem->weight)*1000, //вес
                        'amount' => $packageItem->amount ?? 1, //кол-во
                    ];

                    $weight += (($packageItem->weight ?? 0) ==0 ? 1 : $packageItem->weight)*1000;
                }
            else {
                $packageItems[] = [
                    'name' => $package->title ?? 'Товар', //описание товара
                    'ware_key' => $package->ware_key ?? "Товар".($package->id ?? $index) , //артикул товара
                    'payment' => ['value' => $package->payment ?? 0],
                    'cost' => $package->price ?? 0, //объявленная стоимость (ценность)
                    'weight' => (($package->weight ?? 0) ==0  ? 1 : $package->weight)*1000, //вес
                    'amount' => $package->count ?? 1, //кол-во
                ];

                $weight += (($package->weight ?? 0) ==0  ? 1 : $package->weight)*1000;
            }


            $tmp = [
                "number" => $index,
                'weight' => (($weight ?? 0) == 0 ? $baseDimensions["weight"] : $weight),
                'length' => ($package->length ?? 0) == 0 ? $baseDimensions["length"] : $package->length,
                'width' => ($package->width ?? 0) == 0 ? $baseDimensions["width"] : $package->width,
                'height' => ($package->height ?? 0) == 0 ? $baseDimensions["height"] : $package->height,
                'items' => $packageItems,
                'comment' => '-'
            ];

            $tmpPackages[] = $tmp;

            $index++;
        }


        $data = [
            "uuid" =>  Str::uuid()->toString(),
            "type" => $type,
            "number" => "bot".($orderId ?? Str::uuid()->toString()),
            "tariff_code" => $tariffCode,
            "comment" => $data["comment"] ?? '-',
            "shipment_point" => $from->office["code"],
            "delivery_point" => $to->office->code,
            /*  "delivery_recipient_cost" => [
                  "value" => 100
              ],*/
            "sender" => [
                "company" => $this->bot->company->title ?? $this->bot->bot_domain ?? 'Интернет-магазин',
                "name" => $data["sender_name"] ?? 'CashMan',
               // "tin" => "753608673461",
                 //"email" => "exxxar@gmail.com",
                "phones" => $s_phones,//[["number"=>"+79263183806"]]
            ],
            "recipient" => [
                "name" => $data["recipient_name"],
                 // "email" => "exxxar@gmail.com",
                "phones" =>$r_phones// [["number"=>"+79494320661"]]//$r_phones,
            ],
            "packages" => $tmpPackages
        ];

        Log::info(print_r($data, true));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->authUrl . '/v2/orders', $data);

        Log::info(print_r($response->json(), true));

        return $response->json();
    }


    /**
     * @throws ValidationException
     * @throws RequestException
     */
    public function getOffices($cityCode, $countryCode = 'ru'): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->officeList($cityCode, $countryCode);
    }

    /**
     * @throws ValidationException
     * @throws RequestException
     */
    public function getCities($countryCode = null, $regionCode = null, $city = null): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->cities($countryCode, $regionCode, $city);
    }

    /**
     * @throws ValidationException
     * @throws RequestException
     */
    public function getRegions(): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->regions();
    }

    /**
     * @throws RequestException
     */
    public function calcTariff(array $data)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->calcByTariff($data);
    }

    /**
     * @throws RequestException
     */
    public function calcTariffByCode($tariffCode, array $data)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->calcByTariffCode($tariffCode, $data);
    }

    /**
     * @throws ValidationException
     */
    public function store(array $data): CdekResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "phone" => "required",
            "account" => "required",
            "secure_password" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        //$redirectUri = 'https://your-cashman.com/crm/amo/' . $bot->bot_domain;
        //Account	wqGwiQx0gg8mLtiEKsUinjVSICCjtTEP
        //Secure password	RmAmgvSgSl1yirlz9QupbzOJVqhCxcP5

        $config = json_decode($data["config"] ?? '[]');

        $cdek = Cdek::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
                'account' => $data["account"],
                'secure_password' => $data["secure_password"],
                'is_active' => ($data["is_active"] ?? false) == "true",
                'config' => $config
            ]);


        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();

        if (!is_null($company)) {
            $phones = $company->phones ?? [];
            $phones[0] = $data["phone"];
            $company->phones = $phones;
            $company->save();
        }

        return new CdekResource($cdek);
    }

    /**
     * @param $from1
     * @return string[]
     */
    protected function getLocation($data): array
    {

        $code = $data->office->code ?? null;
        $address = $data->office->location->address_full ?? null;
        $from = ['country_code' => 'RU'];

        /* if (!is_null($from1["address"]))
             $from["address"] = $from1["address"];*/

        $from["code"] = $code;
        $from["address"] = $address;

        return $from;
    }
}
