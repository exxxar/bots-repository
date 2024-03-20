<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
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

class InlineQueryLogicFactory
{
    protected $bot;
    protected $botUser;
    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;


    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
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

        $isUniq = is_null(InlineQuerySlug::query()
            ->where("bot_id", $this->bot->id)
            ->where("command", $data["command"])
            ->first() ?? null);

        if (!$isUniq)
            throw new HttpException(400, "Данная команда уже есть в боте!");

        if (is_null($id))
            $query =
                InlineQuerySlug::query()->create([
                    'bot_id' => $this->bot->id,
                    'command' => $data["command"] ?? null,
                    'description' => $data["description"] ?? null,
                ]);

        else {
            $query = InlineQuerySlug::query()
                ->where("id", $id)
                ->first();

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

            if (isset($data["inline_keyboard"]) && is_null($data["inline_keyboard_id"] ?? null)) {
                $keyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "inline",
                    'slug' => Str::uuid(),
                    'menu' => $data["inline_keyboard"],
                ]);
            }

            if (!is_null($data["inline_keyboard_id"] ?? null))
                $keyboard = BotMenuTemplate::query()->find($data["inline_keyboard_id"]);


            $queryItem = InlineQueryItem::query()
                ->create([
                    'inline_query_slug_id' => $query->id ,
                    'type' => $item["type"] ?? null,
                    'title' => $item["title"] ?? null,
                    'description' => $item["description"] ?? null,
                    'input_message_content' => $item["input_message_content"] ?? null,
                    'inline_keyboard_id' => is_null($keyboard ?? null) ? null : $keyboard->id,
                    'custom_settings' => isset($item["custom_settings"]) ? $item["custom_settings"] : null,
                ]);
        }

        return new InlineQuerySlugResource($query);

    }

    /**
     * @throws HttpException
     */
    public function remove($queryId, $force = false): QuizResource
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

        return new QuizResource($tmp);
    }
}
