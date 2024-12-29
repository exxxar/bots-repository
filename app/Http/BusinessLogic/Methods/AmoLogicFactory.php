<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AmoLogicFactory extends BaseLogicFactory
{

    /**
     * @throws ValidationException
     */
    public function createOrUpdate(array $data): AmoCrmResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "client_id" => "required",
            "client_secret" => "required",
            "auth_code" => "required",
            "subdomain" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        //$redirectUri = 'https://your-cashman.com/crm/amo/' . $bot->bot_domain;


        $amo = AmoCrm::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
                'client_id' => $data["client_id"],
                'client_secret' => $data["client_secret"],
                'auth_code' => $data["auth_code"],
                'redirect_uri' => $this->bot->bot_domain,
                'subdomain' => $data["subdomain"],
                'fields' => is_null($data["fields"] ?? null) ? null : json_decode($data["fields"]),
            ]);

        return new AmoCrmResource($amo);
    }
}
