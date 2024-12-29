<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\InlineQueryItemResource;
use App\Http\Resources\InlineQuerySlugCollection;
use App\Http\Resources\InlineQuerySlugResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotMenuTemplate;
use App\Models\InlineQueryItem;
use App\Models\InlineQuerySlug;
use App\Models\Quiz;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InlineQueryLogicFactory extends BaseLogicFactory
{


    /**
     * @throws HttpException
     */
    public function list($search = null, $size = null, $order = null, $direction = null): InlineQuerySlugCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $queries = InlineQuerySlug::query()
            ->where("bot_id", $this->bot->id);

        if (!is_null($search))
            $queries = $queries->where(function ($q) use ($search) {
                $q->where("command", 'like', "%$search%")
                    ->orWhere("description", 'like', "%$search%");
            });


        $queries = $queries
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new InlineQuerySlugCollection($queries);
    }

    /**
     * @throws ValidationException
     */
    public function store(array $data): InlineQuerySlugResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "command" => "required",
            "description" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $id = $data["id"] ?? null;

        $query = InlineQuerySlug::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($query)) {
            $isQueryUniq = is_null(InlineQuerySlug::query()
                ->where("bot_id", $this->bot->id)
                ->where("command", $data["command"] ?? null)
                ->first() ?? null);

            if (!$isQueryUniq)
                throw new HttpException(403, "Такая команда уже есть в вашем боте!");

            $query =
                InlineQuerySlug::query()->create([
                    'bot_id' => $this->bot->id,
                    'command' => $data["command"] ?? null,
                    'description' => $data["description"] ?? null,
                ]);
        } else {
            $query->update([
                'bot_id' => $this->bot->id,
                'command' => $data["command"] ?? null,
                'description' => $data["description"] ?? null,
            ]);
        }


        if (!isset($data["items"]))
            return new InlineQuerySlugResource($query);

        $items = json_decode($data["items"] ?? '[]');

        foreach ($items as $item) {
            $item = (array)$item;


            if (isset($item["inline_keyboard"]) && is_null($item["inline_keyboard_id"] ?? null)) {
                $keyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "inline",
                    'slug' => Str::uuid(),
                    'menu' => $item["inline_keyboard"]["menu"] ?? null,
                ]);
            }

            if (!is_null($item["inline_keyboard_id"] ?? null))
                $keyboard = BotMenuTemplate::query()->find($item["inline_keyboard_id"]);

            $queryItem = InlineQueryItem::query()
                ->where("id", $item["id"])
                ->first();

            $tmp = [
                'inline_query_slug_id' => $query->id,
                'type' => $item["type"] ?? null,
                'title' => $item["title"] ?? null,
                'description' => $item["description"] ?? null,
                'input_message_content' => $item["input_message_content"] ?? null,
                'inline_keyboard_id' => is_null($keyboard ?? null) ? null : $keyboard->id,
                'custom_settings' => isset($item["custom_settings"]) ? $item["custom_settings"] : null,
            ];

            if (is_null($queryItem))
                $queryItem = InlineQueryItem::query()
                    ->create($tmp);
            else
                $queryItem->update($tmp);
        }

        return new InlineQuerySlugResource($query);

    }

    /**
     * @throws HttpException
     */
    public function remove($queryId, $force = false): InlineQuerySlugResource
    {
        $query = InlineQuerySlug::query()
            ->with(["items"])
            ->where("id", $queryId)
            ->first();


        if (is_null($query))
            throw new HttpException(404, "Команда не найдена");

        $tmp = $query;

        foreach ($query->items ?? [] as $item) {
            $item->delete();
        }

        $query->delete();

        return new InlineQuerySlugResource($tmp);
    }


    /**
     * @throws HttpException
     */
    public function removeItem($queryItemId, $force = false): InlineQueryItemResource
    {
        $query = InlineQueryItem::query()
            ->where("id", $queryItemId)
            ->first();


        if (is_null($query))
            throw new HttpException(404, "Команда не найдена");
        $tmp = $query;
        $query->delete();

        return new InlineQueryItemResource($tmp);
    }
}
