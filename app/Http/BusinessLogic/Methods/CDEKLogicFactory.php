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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use Symfony\Component\HttpKernel\Exception\HttpException;
use CdekSDK2\BaseTypes;

class CDEKLogicFactory extends BaseLogicFactory
{
    use LogicUtilities;


    protected function auth(): ?\CdekSDK2\Client
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        if (is_null($this->bot->cdek ?? null))
            throw new HttpException(404, "СДЭК-параметры не заданы!");

        $account = $this->bot->cdek->account ?? null;
        $secure = $this->bot->cdek->secure_password ?? null;

        $client = new Client();
        $cdek = new \CdekSDK2\Client($client);
        // $cdek->setAccount($account);
        //  $cdek->setSecure($secure);
        $cdek->setTest(true);


        try {
            $cdek->authorize();

            return $cdek;
        } catch (AuthException $e1) {
            //Авторизация не выполнена, не верные account и secure
            Log::info($e1->getMessage());
        } catch (RequestException $e2) {
            Log::info($e2->getMessage());
        }

        return null;
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
    protected function officeList($cityCode, $countryCode = 'ru', $page = 0, $size = 100): mixed
    {
        $cdek = $this->auth();

        $result = $cdek->offices()
            ->getFiltered([
                'country_code' => $countryCode,
                'city_code' => $cityCode,
                'size' => $size,
                'page' => $page]);

        return $result->isOk() ?
            $cdek->formatResponseList($result, \CdekSDK2\Dto\PickupPointList::class)->items :
            [];

    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    protected function cities($countryCode = null, $regionCode = null, $city = null): mixed
    {
        $cdek = $this->auth();


        $filter = [];

        if (!is_null($countryCode))
            $filter['country_code'] = $countryCode;

        if (!is_null($regionCode))
            $filter['region_code'] = $regionCode;

        if (!is_null($city))
            $filter['city'] = $city;

        $result = is_null($countryCode) ?
            $cdek->cities()->get() :
            $cdek->cities()->getFiltered($filter);


        return $result->isOk() ?
            $cdek->formatResponseList($result, \CdekSDK2\Dto\CityList::class)->items :
            [];
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    protected function regions(): mixed
    {
        $cdek = $this->auth();

        $result = $cdek
            ->cities()
            ->getFiltered(["country_codes" => "ru", 'page' => 0, 'size' => 1000]);

        return $result->isOk() ?
            $cdek->formatResponseList($result, \CdekSDK2\Dto\RegionList::class)->items :
            [];

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

        $cdek = $this->auth();


        $from = $this->getLocation(json_decode($data["from"]));
        $to = $this->getLocation(json_decode($data["to"]));

        $packages = [];


        foreach (json_decode($data["packages"]) as $item) {
            $item = (object)$item;
            $packages[] = Package::create([
                'weight' => $item->weight,
                'length' => $item->length,
                'width' => $item->width,
                'height' => $item->height,
            ]);
        }

        $tariff = Tarifflist::create([]);
        $tariff->date = (new \DateTime())->format(\DateTime::ISO8601);
        $tariff->type = Tarifflist::TYPE_ECOMMERCE;
        $tariff->currecy = Currencies::RUBLE;
        $tariff->lang = Tarifflist::LANG_RUS;
        $tariff->from_location = Location::create($from);
        $tariff->to_location = Location::create($to);
        $tariff->packages = $packages;

        $result = $cdek
            ->calculator()
            ->add($tariff);

        return $result->isOk() ?
            $cdek->formatResponseList($result, \CdekSDK2\Dto\TariffList::class)->tariff_codes ?? [] :
            [];


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

        foreach ($basket as $item) {
            $item = $item->product;

            $dimension = !is_null($item->dimension ?? null) ?
                (object)$item->dimension ?? null : null;

            $packages[] = Package::create([
                'number' => $item->id,
                'weight' => $dimension->weight ?? 1000,
                'length' => $dimension->length ?? 10,
                'width' => $dimension->width ?? 20,
                'height' => $dimension->height ?? 20,
            ]);
        }

        /*  //todo: удалить
          $packages[] = Package::create([
              'number' => 1,
              'weight' => 5000,
              'length' => 40,
              'width' => 40,
              'height' => 40,

          ]);*/


        $tariff = Tarifflist::create([]);
        $tariff->date = (new \DateTime())->format(\DateTime::ISO8601);
        $tariff->type = 1;
        $tariff->currecy = Currencies::RUBLE;
        $tariff->lang = 'rus';
//Номера тарифов есть в документации к API: https://api-docs.cdek.ru/63345430.html
        $tariff->from_location = Location::create($from);
        $tariff->to_location = Location::create($to);
        $tariff->packages = $packages;

        $result = $cdek
            ->calculator()
            ->add($tariff);


        return $result->isOk() ? Collection::make(json_decode($result->getBody())->tariff_codes)
            ->where("tariff_code", $tariffCode)
            ->first() : null;

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


        $type = 1;//($data["is_shop_mode"] ?? false) == "true" ? 1 : 2;
        $tariff = $data["tariff"];
        $from = $data["from"];
        $to = $data["to"];
        $packages = $data["packages"];

        $tariffCode = $tariff->tariff_code ?? 1;

        Log::info("tariffCode=>" . print_r($tariffCode, true));

        if ($validator->fails())
            throw new ValidationException($validator);

        $s_phones = [];

        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();


        foreach (($company->phones ?? []) as $item) {
            $s_phones[] = BaseTypes\Phone::create(['number' => $item]);
        }

        $r_phones = [];

        foreach (($data["recipient_phones"] ?? []) as $item) {
            $r_phones[] = BaseTypes\Phone::create(['number' => $item]);
        }

        $baseDimensions = $this->bot->cdek->config->base_dimensions ?? [
            "height" => 15,
            "width" => 15,
            "length" => 15,
            "weight" => 1,
        ];
        // dd($from);

        //  $from = $this->getLocation($from);
        //$to = $this->getLocation($to);

        $tmpPackages = [];

        $test = [];
        $index = 1;
        foreach ($packages as $package) {
            $package = (object)$package;

            $weight = $package->weight == 0 ? 0 : $package->weight;
            $packageItems = [];

            if (count($package->items ?? []) > 0)
                foreach ($package->items as $packageItem) {
                    $packageItem = (object)$packageItem;
                    $packageItems[] = BaseTypes\Item::create([
                        'name' => $packageItem->name ?? 'Товар', //описание товара
                        'ware_key' => $packageItem->ware_key ?? '', //артикул товара
                        'payment' => BaseTypes\Money::create(['value' => $packageItem->payment ?? 0]),
                        'cost' => $packageItem->price ?? 0, //объявленная стоимость (ценность)
                        'weight' => $packageItem->weight ?? 0, //вес
                        'amount' => $packageItem->amount ?? 1, //кол-во
                    ]);

                    $weight += $packageItem->weight ?? 0;
                }
            else {
                $packageItems[] = BaseTypes\Item::create([
                    'name' => $package->title ?? 'Товар', //описание товара
                    'ware_key' => $package->ware_key ?? Str::uuid(), //артикул товара
                    'payment' => BaseTypes\Money::create(['value' => $package->payment ?? 0]),
                    'cost' => $package->price ?? 0, //объявленная стоимость (ценность)
                    'weight' => ($package->weight ?? 0) ? 1 : $package->weight, //вес
                    'amount' => $package->count ?? 1, //кол-во
                ]);

                $weight += ($package->weight ?? 0) ? 1 : $package->weight;
            }


            $tmp = [
                "number" => $index,
                'weight' => (($weight ?? 0) == 0 ? $baseDimensions["weight"] : $weight) * 1000,
                'length' => ($package->length ?? 0) == 0 ? $baseDimensions["length"] : $package->length,
                'width' => ($package->width ?? 0) == 0 ? $baseDimensions["width"] : $package->width,
                'height' => ($package->height ?? 0) == 0 ? $baseDimensions["height"] : $package->height,
                'items' => $packageItems,
                'comment' => '-'
            ];

            $test[] = $tmp;
            $tmpPackages[] = Package::create($tmp);

            $index++;
        }

        Log::info("From=>" . print_r($from, true));
        Log::info("TO=>" . print_r($to, true));

        Log::info("shop_mode=>" . print_r($type, true));
        Log::info("package=>" . print_r($test, true));

        $order = BaseTypes\Order::create([
            //  'number' => $data["id"] ?? null,
            "uuid" => Str::uuid(),
            'type' => $type,
            "number" => $orderId ?? Str::uuid(),
            'tariff_code' => $tariffCode ?? '1',
            "comment" => $data["comment"] ?? '-',
            'sender' => BaseTypes\Contact::create([
                'company' => $this->bot->company->title ?? $this->bot->bot_domain ?? 'Интернет-магазин',
                'name' => $data["sender_name"] ?? 'CashMan',
                'phones' => $s_phones,
            ]),
            'recipient' => BaseTypes\Contact::create([
                'name' => $data["recipient_name"],
                'phones' => $r_phones,
                "passport_series" => "",
                "passport_number" => "",
                "passport_date_of_issue" => "",
                "passport_organization" => "",
            ]),
            'shipment_point' => $from->office["code"],
            'delivery_point' => $to->office->code,
            // 'from_location' => BaseTypes\Location::create((array)$from),
            //'to_location' => BaseTypes\Location::create((array)$to),
            'packages' => $tmpPackages
        ]);


        $cdek = $this->auth();
        $result = $cdek->orders()
            ->add($order);

        Log::info("test cdek order=>" . print_r($result, true));

        return $result->isOk() ?
            $cdek->formatResponse($result, BaseTypes\Order::class)->entity :
            null;

    }


    /**
     * @throws ValidationException
     * @throws RequestException
     */
    public function getOffices($cityCode, $countryCode = 'ru', $page = 0, $size = 100): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->officeList($cityCode, $countryCode, $page, $size);
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
    public function getRegions($countryCode = null, $size = 2): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        return $this->regions($countryCode, $size);
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
