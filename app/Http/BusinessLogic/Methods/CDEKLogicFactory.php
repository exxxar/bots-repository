<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\CdekResource;
use App\Models\AmoCrm;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\Cdek;
use CdekSDK2\BaseTypes\Location;
use CdekSDK2\BaseTypes\Package;
use CdekSDK2\BaseTypes\Tariff;
use CdekSDK2\BaseTypes\Tarifflist;
use CdekSDK2\Constraints\Currencies;
use CdekSDK2\Exceptions\AuthException;
use CdekSDK2\Exceptions\RequestException;
use GuzzleHttp\Client;
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

        $from = $this->getLocation((object)[
            "region" => $cdekSettings->region,
            "city" => $cdekSettings->city,
            "office" => $cdekSettings->office,
        ]);


        $to = $this->getLocation((object)$data["to"]);


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

            if (!is_null($dimension))
                $packages[] = Package::create([
                    'weight' => $item->weight,
                    'length' => $item->length,
                    'width' => $item->width,
                    'height' => $item->height,
                ]);
        }

        //todo: удалить
        $packages[] = Package::create([
            'weight' => 5000,
            'length' => 20,
            'width' => 20,
            'height' => 20,
        ]);

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
                'weight' => $item->weight*1000,
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
    public function createOrder(array $data)
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

        $type = ($data["is_shop_mode"] ?? false) == "true";
        $tariff = json_decode($data["tariff"] ?? '[]');
        $from = json_decode($data["from"] ?? '[]');
        $to = json_decode($data["to"] ?? '[]');
        $packages = json_decode($data["packages"] ?? '[]');

        $tariffCode = $tariff->tariff_code ?? 1;


        if ($validator->fails())
            throw new ValidationException($validator);

        $s_phones = [];


        foreach (json_decode($data["sender_phones"] ?? '[]') as $item) {
            $s_phones[] = BaseTypes\Phone::create(['number' => $item]);
        }

        $r_phones = [];

        foreach (json_decode($data["recipient_phones"] ?? '[]') as $item) {
            $r_phones[] = BaseTypes\Phone::create(['number' => $item]);
        }

        $from = $this->getLocation($from);
        $to = $this->getLocation($to);


        $tmpPackages = [];

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
            $tmpPackages[] = Package::create([
                "number" => Str::uuid(),
                'weight' => $weight*1000,
                'length' => $package->length ?? 0,
                'width' => $package->width ?? 0,
                'height' => $package->height ?? 0,
                'items' => $packageItems
            ]);
        }

        $order = BaseTypes\Order::create([
            //  'number' => $data["id"] ?? null,
            'type' => $type,
            "number" => Str::uuid(),
            'tariff_code' => $tariffCode ?? '1',
            "comment" => $data["comment"] ?? '-',
            'sender' => BaseTypes\Contact::create([
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
            'from_location' => BaseTypes\Location::create($from),
            'to_location' => BaseTypes\Location::create($to),
            'packages' => $tmpPackages
        ]);


        $cdek = $this->auth();

        $result = $cdek->orders()
            ->add($order);


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


        return new CdekResource($cdek);
    }

    /**
     * @param $from1
     * @return string[]
     */
    protected function getLocation($data): array
    {

        $cityCode = $data->city["code"] ?? null;


        $address = $data->address ?? null;
        $from = ['country_code' => 'RU'];

        /* if (!is_null($from1["address"]))
             $from["address"] = $from1["address"];*/
        if (!is_null($cityCode))
            $from["code"] = $cityCode;

        if (!is_null($address))
            $from["address"] = $address;

        return $from;
    }
}
