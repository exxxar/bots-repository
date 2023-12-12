<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\YClientResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\YClients;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Yclients\YclientsApi;

class YClientLogicFactory
{
    protected $bot;

    const URL = "https://api.yclients.com/api/v1";

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

    private function auth($yClient): ?string
    {

        if (is_null($yClient))
            throw new HttpException(403, "Данные подключения не указаны");

        $login = $yClient->login ?? null;
        $password = $yClient->password ?? null;
        $tokenPartner = $yClient->partner_token ?? null;

        $response = Http::withHeaders([
            'Authorization' => "Bearer $tokenPartner",
            'Content-Type' => 'application/json',
            'Accept' => "application/vnd.yclients.v2+json"
        ])->asJson()->post(self::URL . '/auth', [
            "login" => "$login",
            "password" => "$password",
        ]);

        return $response->object()->data->user_token ?? null;
    }

    /**
     * @throws ValidationException
     */
    public function createCompany(array $data)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $yClient = YClients::query()->find($this->bot->id);

        $validator = Validator::make($data, [
            "title" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $userToken = $this->auth($yClient);
        $tokenPartner = $yClient->partner_token ?? null;

        $response = Http::withHeaders([
            'Authorization' => "Bearer $tokenPartner, User $userToken",
            'Content-Type' => 'application/json',
            'Accept' => "application/vnd.yclients.v2+json"
        ])->asJson()->post(self::URL . '/companies', [
            "title" => $data["title"] ?? null,
            "country_id" => $data["country_id"] ?? null,
            "city_id" => $data["city_id"] ?? null,
            "address" => $data["address"] ?? null,
            "site" => $data["site"] ?? null,
            "coordinate_lat" => $data["coordinate_lat"] ?? null,
            "coordinate_lot" => $data["coordinate_lot"] ?? null,
            "short_descr" => $data["short_descr"] ?? null,
        ]);

        return $response->object()->data ?? null;
    }

    /**
     * @throws ValidationException
     */
    public function createClient(array $data)
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $yClient = YClients::query()->find($this->bot->id);

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $userToken = $this->auth($yClient);
        $tokenPartner = $yClient->partner_token ?? null;

        $company = $yClient->company ?? null;

        if (!is_null($company))
            $response = Http::withHeaders([
                'Authorization' => "Bearer $tokenPartner, User $userToken",
                'Content-Type' => 'application/json',
                'Accept' => "application/vnd.yclients.v2+json"
            ])->asJson()->post(self::URL . '/clients/' . $company, [
                "name" => $data["name"] ?? null,
                "surname" => $data["surname"] ?? null,
                "patronymic" => $data["patronymic"] ?? null,
                "phone" => $data["phone"] ?? null,
                "email" => $data["email"] ?? null,
                "sex_id" => $data["sex_id"] ?? null,
                "importance_id" => $data["importance_id"] ?? null,
                "discount" => $data["discount"] ?? null,
                "card" => $data["card"] ?? null,
                "birth_date" => $data["birth_date"] ?? null,
                "comment" => $data["comment"] ?? null,
                "spent" => $data["spent"] ?? null,
                "balance" => $data["balance"] ?? null,
                "sms_check" => $data["sms_check"] ?? null,
                "sms_not" => $data["sms_not"] ?? null,
                "categories" => $data["categories"] ?? null,
                "custom_fields" => $data["custom_fields"] ?? null,
            ]);

        return $response->object()->data ?? null;
    }

    /**
     * @throws ValidationException
     */
    public function createOrUpdate(array $data): YClientResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "login" => "required",
            "password" => "required",
            "partner_token" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $yClient = YClients::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
                'login' => $data["login"],
                'password' => $data["password"],
                'partner_token' => $data["partner_token"],
                'company' => $data["company"],
                'fields' => isset($data["fields"]) ? json_decode($data["fields"] ?? '[]') : null,
            ]);

        return new YClientResource($yClient);
    }
}
