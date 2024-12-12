<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\CdekResource;
use App\Models\AmoCrm;
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
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use CdekSDK2\BaseTypes;

class CDEKLogicFactory
{
    use LogicUtilities;

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
    public function setBot($bot = null): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }


    /**
     * @throws HttpException
     */
    public function setBotUser($botUser = null): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

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
    protected function officeList($cityCode, $countryCode = 'ru', $page = 0, $size = 100  ): mixed
    {
        $cdek = $this->auth();

        $result =  $cdek->offices()
            ->getFiltered([
                'country_code' => $countryCode,
                'city_code'=>$cityCode,
                'size'=>$size,
                'page'=>$page ]);

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
            ->getFiltered(["country_codes"=>"ru",'page'=>0,'size'=>1000]);

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


        $from = $this->getLocation($data["from"]);
        $to = $this->getLocation($data["to"]);

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
            $cdek->formatResponseList($result, \CdekSDK2\Dto\TariffList::class)->tariff_codes ?? []:
            [];


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
            "packages" => "required|array",
            "packages.*.weight" => "required|number",
            "packages.*.length" => "required|number",
            "packages.*.height" => "required|number",
            "packages.*.width" => "required|number",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $cdek = $this->auth();

        $from = $this->getLocation($data["from"]);
        $to = $this->getLocation($data["to"]);

        $packages = [];

        foreach ($data["packages"] as $item) {
            $item = (object)$item;
            $packages[] = Package::create([
                'weight' => $item->weight,
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
    public function createOrder(array $data): ?\CdekSDK2\Dto\Response
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "id" => "required",
            "tariff_code" => "required",
            "sender_name" => "required",
            "recipient_name" => "required",
            "recipient_phones" => "required|array",
            "from_location" => "required",
            "to_location" => "required",
            "packages" => "required",
        ]);

        $tariffCode = json_decode($data["tariff"])->tariff_code ?? 1;
        dd($tariffCode);

        if ($validator->fails())
            throw new ValidationException($validator);

        $r_phones = [];

        foreach ($data["recipient_phones"] as $item) {
            $r_phones[] = BaseTypes\Phone::create(['number' => $item]);
        }

        $from = $this->getLocation($data["from"]);
        $to = $this->getLocation($data["to"]);

        $packages = [];

        foreach ($data["packages"] as $item) {
            $item = (object)$item;

            $packageItems = [];
            if (count($item->items ?? []) > 0)
                foreach ($item->items as $packageItem) {
                    $packageItem = (object)$packageItem;
                    $packageItems[] = BaseTypes\Item::create([
                        'name' => $packageItem->name ?? 'Товар', //описание товара
                        'ware_key' => $packageItem->ware_key ?? '', //артикул товара
                        'payment' => BaseTypes\Money::create(['value' => $packageItem->payment ?? 0]),
                        'cost' => $packageItem->cost ?? 0, //объявленная стоимость (ценность)
                        'weight' => $packageItem->weight ?? 1000, //вес
                        'amount' => $packageItem->amount ?? 1, //кол-во
                    ]);
                }
            $packages[] = Package::create([
                'weight' => $item->weight,
                'length' => $item->length,
                'width' => $item->width,
                'height' => $item->height,
                'items' => $packageItems
            ]);
        }

        $order = BaseTypes\Order::create([
          //  'number' => $data["id"] ?? null,
            'tariff_code' => $tariffCode ?? '1',
            'sender' => BaseTypes\Contact::create([
                'name' => $data["sender_name"] ?? 'CashMan',
            ]),
            'recipient' => BaseTypes\Contact::create([
                'name' => $data["recipient_name"],
                'phones' => $r_phones,
                "passport_series"=>"",
                "passport_number"=>"",
                "passport_date_of_issue"=>"",
                "passport_organization"=>"",
            ]),
            'from_location' => BaseTypes\Location::create($from),
            'to_location' => BaseTypes\Location::create($to),
            'packages' => $packages
        ]);


        $cdek = $this->auth();

        $result = $cdek->orders()
            ->add($order);

        return $result->isOk() ?
            $cdek->formatResponse($result, BaseTypes\Order::class) :
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

        return $this->cities($countryCode, $regionCode,  $city);
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

        $cdek = Cdek::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
                'account' => $data["account"],
                'secure_password' => $data["secure_password"],
                'is_active' => ($data["is_active"] ?? false) == "true",

            ]);


        return new CdekResource($cdek);
    }

    /**
     * @param $from1
     * @return string[]
     */
    protected function getLocation($data): array
    {
        $data = json_decode($data);
        $cityCode = $data->city->code ?? null;
        $from = ['country_code' => 'RU'];

       /* if (!is_null($from1["address"]))
            $from["address"] = $from1["address"];*/
        if (!is_null($cityCode))
            $from["code"] = $cityCode;
        return $from;
    }
}
