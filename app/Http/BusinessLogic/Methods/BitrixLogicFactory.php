<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\BitrixService;
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

class BitrixLogicFactory extends BaseLogicFactory
{

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

        $bitrix = Bitrix::query()
            ->where($id ?? null)->first();

        if (is_null($bitrix))
            $bitrix = Bitrix::query()->create($tmp);
        else {
            $bitrix
                ->update($tmp);

            $bitrix->refresh();
        }

        if ($bitrix->is_active) {
            $tmps = Bitrix::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_active", true)
                ->get();

            foreach ($tmps as $tmp)
                if ($tmp->id != !$bitrix->id) {
                    $tmp->is_active = false;
                    $tmp->save();
                }

        }


        return new BitrixResource($bitrix);
    }


    /**
     * @throws ValidationException
     */
    public function addProducts(array $data)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "lead_id" => "required",
            "products" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $connection = Bitrix::query()
            ->where("bot_id", $this->bot->id)
            ->where("is_active", true)
            ->first();


        $bitrix = new BitrixService($connection->url);
        $productsForBitrix = [];
        foreach ($data["products"] as $product) {
            $productData = [
                'NAME' => $product["title"] ?? '-',
                'CURRENCY_ID' => 'RUB',
                'PRICE' => $product["price"] ?? 0,
                'DESCRIPTION' => $product["description"] ?? '-',
                'MEASURE' => 6,
                'QUANTITY' => $product["count"] ?? 0 // Единица измерения (шт.)
            ];

            $productId = $bitrix->addProduct($productData)["result"] ?? null;

            $productsForBitrix[] = [
                "PRODUCT_ID" => $productId,
                "PRICE" => $product["price"] ?? 0,
                "QUANTITY" => $product["count"] ?? 0,
            ];
        }

        $result = $bitrix->addProductToLead($data["lead_id"], $productsForBitrix);


    }

    /**
     * @throws ValidationException
     */
    public function check(array $data): int
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "url" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $name = mb_split(" ", $this->botUser->name);

        $url = $data["url"];

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
                        "UTM_SOURCE" => "Бот " . ($this->bot->bot_domain ?? '-'),
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

            return $result->status();
        } catch (\Exception $exception) {

            return 400;
        }

    }

    /**
     * @throws HttpException
     */
    public function statusList(string $url)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");


        try {
            $result = Http::asJson()
                ->post("$url/crm.status.list ", [
                    "order" => (object)["SORT" => "ASC"],
                    "filter" => (object)["ENTITY_ID" => "SOURCE"],
                ]);

            return $result->json();
        } catch (\Exception $exception) {

            return null;
        }

    }

    /**
     * @throws ValidationException
     */
    public function addContact(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");


        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $connection = Bitrix::query()
            ->where("bot_id", $this->bot->id)
            ->where("is_active", true)
            ->first();


        $url = $connection->url ?? null;

        $bitrix = new BitrixService($url);
        $contactData = [
            'NAME' => $data["name"],
            'SECOND_NAME' => $data["sname"] ?? '',
            'LAST_NAME' => $data["lname"] ?? '',
            'TYPE_ID' => "CLIENT",
            'PHONE' => [['VALUE' => $data["phone"], 'VALUE_TYPE' => 'WORK']],
            'EMAIL' => [['VALUE' => $data["email"], 'VALUE_TYPE' => 'WORK']]
        ];

        return $bitrix->upsertContact($contactData)["result"];


    }

    public function addLead(string $title = null, $contactId = null): ?array
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $connection = Bitrix::query()
            ->where("bot_id", $this->bot->id)
            ->where("is_active", true)
            ->first();


        $url = $connection->url ?? null;

        $name = mb_split(" ", $this->botUser->name);

        $tmp = (object)[
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
        ];

        if (!is_null($contactId))
            $tmp["CONTACT_ID"] = [$contactId];

        try {
            $result = Http::asJson()
                ->post("$url/crm.lead.add.json", [
                    'fields' => $tmp
                ]);


        } catch (\Exception $exception) {
            Log::info("Что-то не так с Bitrix");
        }


        return $result["result"] ?? null;
    }
}
