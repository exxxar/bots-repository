<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AmoLogicFactory
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
            ]);

        return new AmoCrmResource($amo);
    }
}
