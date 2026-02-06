<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Classes\HasSettings;
use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotSlugLogicFactory extends BaseLogicFactory
{
    use HasSettings;

    public function globals(): BotMenuSlugCollection
    {
        if (!is_null($this->botUser)) {
            $manager = $this->botUser->manager ?? null;

            if (is_null($manager))
                throw new HttpException(400, "Не является менеджером!");

            $ids = Collection::make($manager->scripts)->pluck("id");


            $slugs = BotMenuSlug::query()
                ->where("is_global", true)
                ->whereIn("id", $ids)
                ->whereNull("bot_id")
                ->get()
                ->unique("slug");
            return new BotMenuSlugCollection($slugs);
        }

        $slugs = BotMenuSlug::query()
            ->where("is_global", true)
            ->whereNull("bot_id")
            ->get()
            ->unique("slug");

        return new BotMenuSlugCollection($slugs);
    }

    /**
     * @throws HttpException
     */
    public function list($search = null, $size = null, bool $needGlobal = null, $needDeleted = false): BotMenuSlugCollection
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $botMenuSlugs = BotMenuSlug::query();

        if ($needDeleted)
            $botMenuSlugs = $botMenuSlugs->withTrashed();

        $botMenuSlugs = $botMenuSlugs
            ->where("bot_id", $this->bot->id);

        if (!is_null($needGlobal))
            $botMenuSlugs = $botMenuSlugs->where("is_global", $needGlobal);

        if (!is_null($search))
            $botMenuSlugs = $botMenuSlugs
                ->where(function ($q) use ($search) {
                    $q->where("command", "like", "%$search%")
                        ->orWhere("comment", "like", "%$search%");

                })
                ->orWhere("id", "like", "%$search%");

        $botMenuSlugs = $botMenuSlugs
            ->orderBy("created_at", "desc")
            ->paginate($size);


        return new BotMenuSlugCollection($botMenuSlugs);
    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function relocateActionData(array $data): bool
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "slug_sender_id" => "required",
            "slug_recipient_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $slugSender = BotMenuSlug::query()
            ->find($data["slug_sender_id"]);

        $slugRecipient = BotMenuSlug::query()
            ->find($data["slug_recipient_id"]);

        if (is_null($slugSender) || is_null($slugRecipient))
            throw new HttpException(404, "Скрипт не найден!");

        $slugRecipient->config = $slugSender->config ?? [];
        $slugRecipient->save();

        $tmp = (object)$data;

        $actions = ActionStatus::query()
            ->where("bot_id", $this->bot->id)
            ->where("slug_id", $tmp->slug_sender_id)
            ->get();

        if (empty($actions))
            return false;

        foreach ($actions as $action)
            ActionStatus::query()
                ->updateOrCreate([
                    'user_id' => $action->user_id,
                    'bot_id' => $action->bot_id,
                    'slug_id' => $tmp->slug_recipient_id,
                ], [
                    'max_attempts' => $action->max_attempts ?? 0,
                    'current_attempts' => $action->current_attempts ?? 0
                ]);

        return true;
    }

    /**
     * @throws HttpException
     */
    public function allSlugList(): BotMenuSlugCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $botMenuSlugs = BotMenuSlug::query()
            ->where("bot_id", $this->bot->id)
            ->where("is_global", true)
            ->orderBy("created_at", "desc")
            ->get();

        return new BotMenuSlugCollection($botMenuSlugs);

    }

    /**
     * @throws HttpException
     */
    public function globalList($search = null, $size = null): BotMenuSlugCollection
    {

        $size = $size ?? config('app.results_per_page');


        $botMenuSlugs = BotMenuSlug::query()
            ->where("is_global", true)
            ->whereNull("bot_id");

        if (!is_null($this->botUser ?? null)) {
            $manager = $this->botUser->manager ?? null;

            if (is_null($manager))
                throw new HttpException(400, "Не является менеджером!");

            $ids = Collection::make($manager->scripts)->pluck("id");

            $botMenuSlugs = $botMenuSlugs
                ->whereIn("id", $ids);
        }

        if (!is_null($search))
            $botMenuSlugs = $botMenuSlugs
                ->where(function ($q) use ($search) {
                    $q->where("command", "like", "%$search%")
                        ->orWhere("comment", "like", "%$search%");
                });

        $botMenuSlugs = $botMenuSlugs
            ->orderBy("created_at", "desc")
            ->paginate($size);

        return new BotMenuSlugCollection($botMenuSlugs);

    }

    /**
     * @throws HttpException
     */
    public function restore($slugId): BotMenuSlugResource
    {

        $botMenuSlug = BotMenuSlug::query()
            ->withTrashed()
            ->find($slugId);
        if (is_null($botMenuSlug))
            throw new HttpException(404, "Команда не найдена!");

        $botMenuSlug->deleted_at = null;
        $botMenuSlug->save();

        return new BotMenuSlugResource($botMenuSlug);
    }

    /**
     * @throws HttpException
     */
    public function destroy($slugId): BotMenuSlugResource
    {

        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            throw new HttpException(404, "Команда не найдена!");

        $botMenuSlug->deleted_at = Carbon::now();
        $botMenuSlug->save();

        return new BotMenuSlugResource($botMenuSlug);
    }

    /**
     * @throws HttpException
     */
    public function duplicate($slugId): BotMenuSlugResource
    {
        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            throw new HttpException(404, "Команда не найдена!");

        $botMenuSlug = $botMenuSlug->replicate();
        $botMenuSlug->save();

        return new BotMenuSlugResource($botMenuSlug);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function create(array $pageData): BotMenuSlugResource
    {
        $validator = Validator::make($pageData, [
            "command" => "required",
            "slug" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $tmp = (object)$pageData;

        $tmp->config = json_decode($tmp->config ?? '[]');
        $tmp->is_global = $tmp->is_global == "true";
        $tmp->parent_slug_id = $tmp->id;
        $tmp->slug = Str::uuid();

        $slug = BotMenuSlug::query()->create((array)$tmp);

        return new BotMenuSlugResource($slug);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function update(array $pageData): BotMenuSlugResource
    {

        $validator = Validator::make($pageData, [
            "id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $tmp = (object)$pageData;

        $slug = BotMenuSlug::query()->find($tmp->id);
        if (is_null($slug))
            throw new HttpException(404, "Команда не найдена!");

        $tmp->is_global = $tmp->is_global ?? false;

        $tmp->config = json_decode($tmp->config);

        $slug->update((array)$tmp);

        return new BotMenuSlugResource($slug);
    }

    /**
     * @throws HttpException
     */
    public function updateScriptParams(array $data): BotSecurityResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Не все параметры функции заданы!");


     /*   $data["can_use_cash"] = (($data["can_use_cash"] ?? false) == "true");
        $data["need_automatic_delivery_request"] = (($data["need_automatic_delivery_request"] ?? false) == "true");
        $data["can_use_card"] = (($data["can_use_card"] ?? false) == "true");
        $data["can_use_sbp"] = (($data["can_use_sbp"] ?? false) == "true");
        $data["is_disabled"] = (($data["is_disabled"] ?? false) == "true") ;
        $data["is_edit_mode"] = (($data["is_edit_mode"] ?? false) == "true");
        $data["can_buy_after_closing"] = (($data["can_buy_after_closing"] ?? false) == "true");
        $data["need_pay_after_call"] = (($data["need_pay_after_call"] ?? false) == "true");
        $data["need_hide_disabled_products"] = (($data["need_hide_disabled_products"] ?? false) == "true");
        $data["need_hide_delivery_period"] = (($data["need_hide_delivery_period"] ?? false) == "true");
        $data["need_bonuses_section"] = (($data["need_bonuses_section"] ?? false) == "true");
        $data["can_use_booking"] = (($data["can_use_booking"] ?? false) == "true");*/

        $data["can_use_cash"] = filter_var($data["can_use_cash"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["need_automatic_delivery_request"] = filter_var($data["need_automatic_delivery_request"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["can_use_card"] = filter_var($data["can_use_card"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["can_use_sbp"] = filter_var($data["can_use_sbp"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["is_disabled"] = filter_var($data["is_disabled"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["is_edit_mode"] = filter_var($data["is_edit_mode"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["can_buy_after_closing"] = filter_var($data["can_buy_after_closing"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["need_pay_after_call"] = filter_var($data["need_pay_after_call"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["need_hide_disabled_products"] = filter_var($data["need_hide_disabled_products"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["need_hide_delivery_period"] = filter_var($data["need_hide_delivery_period"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["need_bonuses_section"] = filter_var($data["need_bonuses_section"] ?? false, FILTER_VALIDATE_BOOLEAN);
        $data["can_use_booking"] = filter_var($data["can_use_booking"] ?? false, FILTER_VALIDATE_BOOLEAN);


        $data["price_per_km"] = (int)($data["price_per_km"] ?? 0);
        $data["interval"] = (int)($data["interval"] ?? 1);
        $data["free_shipping_starts_from"] = (int)($data["free_shipping_starts_from"] ?? 0);
        $data["min_base_delivery_price"] = (int)($data["min_base_delivery_price"] ?? 0);
        $data["wheel_of_fortune"] = json_decode($data["wheel_of_fortune"] ?? '[]');
        $data["init_certificate"] = json_decode($data["init_certificate"] ?? '[]');
        $data["subscriptions"] = json_decode($data["subscriptions"] ?? '[]');
        $data["tables_variants"] = json_decode($data["tables_variants"] ?? '[]');
        $data["coffee"] = json_decode($data["coffee"] ?? '[]');
        $data["partners"] = json_decode($data["partners"] ?? '[]');

        $data["sbp"] = json_decode($data["sbp"] ?? '[]');

        if (!is_null($data["payment_token"] ?? null)) {
            $this->bot->payment_provider_token = $data["payment_token"] ?? null;
            $this->bot->save();

        }

        if (!is_null($this->slug)) {
            $slug = BotMenuSlug::query()->find($this->slug->id);
            $tmp = $slug->config ?? [];
            $slug->config = [];
            $slug->save();
        }

        $tmp = $this->validateConfig($tmp ?? []);
        $data = $this->validateConfig($data);

        foreach (array_keys($data) as $key) {
            $tmp[$key] = $data[$key] ?? null;
        }


        $this->setConfig($tmp);


        return new BotSecurityResource($this->bot);
    }
}
