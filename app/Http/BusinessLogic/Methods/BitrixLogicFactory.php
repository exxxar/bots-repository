<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\BitrixCollection;
use App\Http\Resources\BitrixResource;
use App\Http\Resources\IikoResource;
use App\Models\AmoCrm;
use App\Models\Bitrix;
use App\Models\Bot;
use App\Models\Iiko;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BitrixLogicFactory
{
    protected $bot;
    protected $botUser;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;

    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function get(): BitrixCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $bitrix = Bitrix::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        return new BitrixCollection($bitrix);
    }

    /**
     * @throws ValidationException
     */
    public function store(array $data): BitrixResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "url" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $id = $data["id"] ?? null;

        $tmp = [
            'url' => $data["url"] ?? null,
            'is_active' => ($data["is_active"] ?? false) == "true",
            'bot_id' => $this->bot->id,
        ];

        $bitrix = Bitrix::query()->find($id ?? null);

        if (is_null($bitrix))
            $bitrix = Bitrix::query()->create($tmp);
        else {
            $bitrix
                ->update($tmp);

            $bitrix->refresh();
        }


        return new BitrixResource($bitrix);
    }

    public function addLead(string $title = null): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $bs = Bitrix::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($bs) == 0)
            return;

        foreach ($bs as $bitrix) {
            $url = $bitrix->url ?? null;
            $isActive = $bitrix->is_active ?? false;

            if (is_null($url) || !$isActive)
                continue;

            $name = mb_split(" ", $this->botUser->name);

            try {
                $result = Http::asJson()
                    ->post("$url/crm.lead.add.json", [
                        'fields' => (object)[
                            "TITLE" => "Бот " . ($this->bot->bot_domain ?? '-') . ": " . ($title ?? "Новый лид"),
                            "NAME" => $name[0] ?? $this->botUser->name ?? $this->botUser->telegram_chat_id,
                            "LAST_NAME" => $name[1] ?? $this->botUser->username ?? $this->botUser->telegram_chat_id,
                            "ADDRESS" => $this->botUser->address ?? null,
                            "ADDRESS_CITY" => $this->botUser->city ?? null,
                            "ADDRESS_COUNTRY" => $this->botUser->country ?? null,
                            "BIRTHDATE" => $this->botUser->birthday ?? null,
                            "EMAIL" => [
                                (object)[
                                    "VALUE" => $this->botUser->email ?? null,
                                    "VALUE_TYPE" => "CLIENT"
                                ]
                            ],
                            "PHONE" => [
                                (object)[
                                    "VALUE" => $this->botUser->phone ?? null,
                                    "VALUE_TYPE" => "CLIENT"
                                ]
                            ],
                            "WEB" => [

                                (object)[
                                    "VALUE" => "https://t.me/" . $this->bot->bot_domain . "?start=" . base64_encode("003" . $this->botUser->telegram_chat_id),
                                    "VALUE_TYPE" => "BOT"
                                ],
                                (object)[
                                    "VALUE" => !is_null($this->botUser->username) ? "https://t.me/" . $this->botUser->username : null,
                                    "VALUE_TYPE" => "TELEGRAM"
                                ]

                            ],
                        ],

                    ]);
            } catch (\Exception $exception) {
                Log::info("Что-то не так с Bitrix");
            }


        }

    }
}
