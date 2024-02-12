<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GeoLogicFactory
{
    protected $bot;

    public function __construct()
    {
        $this->bot = null;

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
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "address" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $address = $data["address"] ?? '';

        try {
            $res = Http::get("https://geocode.maps.co/search?q=$address");

            $data = $res->json();

            if (empty($data))
                return (object)[
                    "latitude" => 0,
                    "longitude" => 0,
                ];


            return (object)[
                "latitude" => $data[0]["lat"],
                "longitude" => $data[0]["lon"],
            ];


        } catch (\Exception $exception) {
            return (object)[
                "latitude" => 0,
                "longitude" => 0,
            ];
        }

    }

    /**
     * @throws ValidationException
     */
    public function getDistance(array $data): object
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "coords.*.lat" => "required",
            "coords.*.lon" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        Log::info("data in func =>". print_r($data, true));

        $coords = $data["coords"] ?? [];



        $tmpCoords = "";
        $index = 0;
        foreach ($coords as $point) {
            $point = (object)$point;
            $tmpCoords .= "$point->lon,$point->lat" . ($index != count($coords )-1 ? ";" : "");
        }

        try {

            Log::info("distance route "."https://router.project-osrm.org/route/v1/driving/$tmpCoords?alternatives=false");
            $res = Http::get("https://router.project-osrm.org/route/v1/driving/$tmpCoords?alternatives=false");

            $data = $res->json();

            Log::info("distance request to api=>".print_r($data, true));
            if ($data->code != "Ok")
                return (object)[
                    "duration"=>0,
                    "distance"=>0,
                ];


            return (object)[
                "duration"=>$data->routes[0]->duration ?? 0,
                "distance"=>$data->routes[0]->distance ?? 0,
            ];


        } catch (\Exception $exception) {
            return (object)[
                "duration"=>0,
                "distance"=>0,
            ];
        }

    }
}
