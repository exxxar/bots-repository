<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use Illuminate\Support\Facades\Http;
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
                "latitude" => $data[0]->lat,
                "longitude" => $data[0]->lon,
            ];


        } catch (\Exception $exception) {
            return (object)[
                "latitude" => 0,
                "longitude" => 0,
            ];
        }

    }
}
