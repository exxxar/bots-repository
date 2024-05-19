<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\FrontPadResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\FrontPad;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FrontPadLogicFactory
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
    public function store(array $data): FrontPadResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "hook_url" => "required",
            "token" => "required",
        ]);


        if ($validator->fails())
            throw new ValidationException($validator);


        $frontPad = FrontPad::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
                'hook_url' => $data["hook_url"] ?? null,
                'channel' => $data["channel"] ?? null,
                'affiliate' => $data["affiliate"] ?? null,
                'point' => $data["point"] ?? null,
                'token' => $data["token"] ?? null,
            ]);

        return new FrontPadResource($frontPad);
    }
}
