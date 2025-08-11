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
use TypeError;
use Yandex\Geo\Exception;
use Yandex\Geo\Exception\CurlError;
use Yandex\Geo\Exception\ServerError;

class GeoLogicFactory extends BaseLogicFactory
{


    /**
     * @throws ValidationException
     * @throws HttpException
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

        $url = "https://nominatim.openstreetmap.org/search";

        $params = [
            'q' => $address,
            'format' => 'json',
            'limit' => 1
        ];
        $options = [
            'http' => [
                'header' => "User-Agent: CashMan/1.0\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url . '?' . http_build_query($params), false, $context);
        $data = json_decode($response, true);

        if (empty($data)) {
            return (object)[
                'lat' => 0,
                'lon' => 0
            ];
        }

        return (object)[
            'lat' => (float)($data[0]['lat']),
            'lon' => (float)($data[0]['lon'])
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

        $latB = floatval($coords[0] ?? 0);
        $longB = floatval($coords[1] ?? 0);

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
        return $ad * $earth_radius;

    }
}
