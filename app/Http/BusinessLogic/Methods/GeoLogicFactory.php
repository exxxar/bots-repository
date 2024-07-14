<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\YClients;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Yandex\Geo\Exception;
use Yandex\Geo\Exception\CurlError;
use Yandex\Geo\Exception\ServerError;

class GeoLogicFactory
{
    protected $bot;

    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->slug = null;
    }

    public function setSlug($slug): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function getCoords(array $data): object
    {
        if (is_null($this->bot) || is_null($this->slug))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "address" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $address = $data["address"] ?? '';

        $yandex_geocoder = (Collection::make($this->slug->config)
            ->where("key", "yandex_geocoder")
            ->first())["value"] ?? null;

        if (is_null($yandex_geocoder))
            throw new HttpException(403, "Не установлен токен гео-кодера");

        $api = new \Yandex\Geo\Api();

        $api->setToken(trim($yandex_geocoder));
        $api->setQuery(trim($address));

        try {
            $api
                ->setLimit(1)
                ->setLang(\Yandex\Geo\Api::LANG_RU)
                ->load();
        } catch (CurlError $e) {

        } catch (ServerError $e) {

        } catch (Exception $e) {

        }


        $response = $api->getResponse();
        //  $response->getFoundCount(); // кол-во найденных адресов
        //  $response->getQuery(); // исходный запрос
        // $response->getLatitude(); // широта для исходного запроса
        //$response->getLongitude(); // долгота для исходного запроса
        /*
        // Список найденных точек
                $collection = $response->getList();
                foreach ($collection as $item) {
                    $item->getAddress(); // вернет адрес
                    $item->getLatitude(); // широта
                    $item->getLongitude(); // долгота
                    $item->getData(); // необработанные данные*/

        Log::info(print_r([
           "points"=>$response->getList()[0]->getData(),
           "r1"=> $response->getFoundCount(),
           "r2"=> $response->getQuery(),
           "r3"=> $response->getFoundCount(),
           "r4"=> $response->getLatitude(),
           "r5"=> $response->getLongitude(),
        ],true));

        $obj =  $response->getList()[0]->getData();
        return (object)[
            "lat" =>$obj["Latitude"] ?? 0,
            "lon" => $obj["Longitude"] ?? 0
        ];

    }

    /**
     * @throws ValidationException
     */
    public function getDistance($latA, $longA): float
    {
        if (is_null($this->bot) || is_null($this->slug))
            throw new HttpException(403, "Не выполнены условия функции");


        $shopCoords = (Collection::make($this->slug->config)
            ->where("key", "shop_coords")
            ->first())["value"] ?? null;

        $coords = explode(',', $shopCoords);

        $latB = $coords[0] ?? 0;
        $longB = $coords[1] ?? 0;

        $earth_radius = 6372795;
        // перевести координаты в радианы
        $lat1 = $latA * M_PI / 180;
        $lat2 = $latB * M_PI / 180;
        $long1 = $longA * M_PI / 180;
        $long2 = $longB * M_PI / 180;

// косинусы и синусы широт и разницы долгот
        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

// вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

//
        $ad = atan2($y, $x);
        $dist = $ad * $earth_radius;

        return $dist;
    }
}
