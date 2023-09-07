<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotSlugLogicFactory
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

    public function globals(): BotMenuSlugCollection
    {
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
    public function list($search = null, $size = null, bool $needGlobal = null): BotMenuSlugCollection
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $botMenuSlugs = BotMenuSlug::query()
            ->where("bot_id", $this->bot->id);

        if (!is_null($needGlobal))
            $botMenuSlugs = $botMenuSlugs->where("is_global", $needGlobal);

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
    public function globalList($search = null, $size = null): BotMenuSlugCollection
    {

        $size = $size ?? config('app.results_per_page');


            $botMenuSlugs = BotMenuSlug::query()
                ->where("is_global", true)
                ->whereNull("bot_id");

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
    public function destroy($slugId): BotMenuSlugResource
    {

        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            throw new HttpException(404,"Команда не найдена!");

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
            throw new HttpException(404,"Команда не найдена!");

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
            throw new HttpException(404,"Команда не найдена!");

        $tmp->is_global = $tmp->is_global ?? false;

        $tmp->config = json_decode($tmp->config);

        $slug->update((array)$tmp);

        return new BotMenuSlugResource($slug);
    }
}
