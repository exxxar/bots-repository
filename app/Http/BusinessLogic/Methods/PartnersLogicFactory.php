<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\PartnerCollection;
use App\Http\Resources\PartnerResource;
use App\Models\AmoCrm;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\Partner;
use App\Models\ProductCategory;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PartnersLogicFactory extends BaseLogicFactory
{
    public function list(array $data = null): PartnerCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $partners = Partner::query()
            ->with(["products"])
            ->where("bot_id", $this->bot->id)
            ->get();

        return new PartnerCollection($partners);
    }

    public function listOfPartnersCategories(): \Illuminate\Database\Eloquent\Collection|array
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $partnersId = $this->bot->partners()->get()->pluck("bot_partner_id");

        $categories = ProductCategory::query()
            ->with('bot')
            ->whereIn('bot_id', $partnersId)
            ->where('is_active', true)
            ->orderByRaw('LENGTH(title) ASC')
            ->get();

        $grouped = [];

        foreach ($categories as $item) {
            $botId = $item->bot->id ?? 'Без бота'; // на всякий случай, если бот не подгружен

            if (!isset($grouped[$botId])) {
                $grouped[$botId]["categories"] = [];
                $grouped[$botId]["bot"] = (object)[
                    "id" => $item->bot->id,
                    "title" => $item->bot->title,
                ];
            }

            $grouped[$botId]["categories"][] = $item;
        }

        /*  $categories = $categories->map(function ($item) use (&$nameCount) {
              $name = $item->title;
              $item->title = "{$name} ({$item->bot->title})";
              return $item;
          });*/

        //dd($categories);

        return $grouped;

    }

    /**
     * @throws ValidationException
     */
    public function create(array $data): PartnerResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "telegram_domain" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $botPartner = Bot::query()
            ->where("bot_domain", $data["telegram_domain"])
            ->first();

        if (is_null($botPartner))
            throw new HttpException(404, "Бот-партнер не найден в системе!");

        $partner = Partner::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_partner_id", $botPartner->id)
            ->first();

        if (!is_null($partner))
            throw new HttpException(403, "Данные боты уже являются партнерами!");

        $partner = Partner::query()->create(
            [
                'bot_id' => $this->bot->id,
                'bot_partner_id' => $botPartner->id,
                'title' => $botPartner->title,
                'description' => $botPartner->short_description,
                'image' => $botPartner->image,
                'is_active' => true,
                'extra_charge' => 0,
                'config' => [],
                'legal_info' => [],
            ]);

        return new PartnerResource($partner);
    }

    /**
     * @throws ValidationException
     */
    public function update(array $data, $file = null): PartnerResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'id' => "required",
            'bot_partner_id' => "required",
            'title' => "",
            'description' => "",
            'image' => "",
            'is_active' => "",
            'extra_charge' => "",
            'config' => "",
            'legal_info' => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $botPartner = Bot::query()
            ->where("id", $data["bot_partner_id"])
            ->first();

        if (is_null($botPartner))
            throw new HttpException(404, "Бот-партнер не найден в системе!");

        $partner = Partner::query()
            ->where("id", $data["id"])
            ->first();

        if (is_null($partner))
            throw new HttpException(403, "Данные боты уже являются партнерами!");

        if ($file) {
            $slug = $this->bot->company->slug;
            $ext = $file->getClientOriginalExtension();
            $imageName = Str::uuid() . "." . $ext;
            $file->storeAs("/public/companies/$slug/$imageName");
            $data['image'] = $imageName;
        }

        $partner->update(
            [
                'title' => $data["title"] ?? $partner->title,
                'description' => $data["description"] ?? $partner->description,
                'image' => $data["image"] ?? $partner->image,
                'is_active' => ($data["is_active"] ?? false) == "true",
                'extra_charge' => $data["extra_charge"] ?? $partner->extra_charge ?? 0,
                'config' => isset($data["config"]) ? json_decode($data["config"] ?? '[]') : $partner->config,
                'legal_info' => isset($data["legal_info"]) ? json_decode($data["legal_info"] ?? '[]') : $partner->legal_info,
            ]);

        return new PartnerResource($partner);
    }

    /**
     * @throws ValidationException
     */
    public function updateSelf(array $data, $file = null)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'title' => "",
            'description' => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $description = $data["description"] ?? null;
        $title = $data["title"] ?? null;

        $config = $this->bot->config ?? [];
        $partnersConfig = $config["partners"] ?? [];

        $partnersConfig["title"] = $title;
        $partnersConfig["description"] = $description;

        if ($file) {
            $slug = $this->bot->company->slug;
            $ext = $file->getClientOriginalExtension();
            $imageName = Str::uuid() . "." . $ext;
            $file->storeAs("/public/companies/$slug/$imageName");
            $partnersConfig["image"] = $imageName;
        }

        $categories = ProductCategory::query()
            ->where("bot_id", $this->bot->id)
            ->get()
            ->select("title");


        $partnersConfig["categories"] = $categories->toArray();
        $config["partners"] = $partnersConfig;

        $this->bot->config = $config;
        $this->bot->save();

        return $config["partners"];

    }

    /**
     * @throws ValidationException
     */
    public function changeStatus(array $data)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "product_id" => "required",
            "partner_id" => "required",
            "status" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $productId = $data["product_id"];
        $partnerId = $data["partner_id"];
        $status = $data["status"] ?? 0;

        $partner = Partner::query()
            ->where("id", $partnerId)
            ->first();

        if (is_null($partner))
            throw new HttpException(404, "Бот-партнер не найден в системе!");


        $excludes = $partner->config["excludes"] ?? [];
        switch ($status) {
            default:
            case 0:
                $excludes = array_filter($excludes ?? [], fn($v) => $v !== $productId);
                break;
            case 1:
                $excludes[] = $productId;
                break;

        }

        $config = $partner->config;
        $config["excludes"] = $excludes;
        $partner->config = $config;
        $partner->save();

        return $config["excludes"];
    }

    /**
     * @throws ValidationException
     */
    public function updateSettings(array $data)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "is_active" => "required",
            "display_self" => "required",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $isActive = ($data["is_active"] ?? false) == "true";;
        $displaySelf = ($data["display_self"] ?? false) == "true";

        $config = $this->bot->config ?? [];
        $partnersConfig = $config["partners"] ?? [];
        $partnersConfig["is_active"] = $isActive;
        $partnersConfig["display_self"] = $displaySelf;

        $config["partners"] = $partnersConfig;

        $this->bot->config = $config;
        $this->bot->save();

        if (!$isActive) {
            $basket = Basket::query()
                ->where("bot_id", $this->bot->id)
                ->get();

            foreach ($basket as $item)
                $item->delete();


        }

        return $config["partners"];
    }
}
