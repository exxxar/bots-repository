<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\BotPageCollection;
use App\Http\Resources\BotPageResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\Company;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class BotPageLogicFactory
{

    use LogicUtilities;

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
     * @throws HttpException
     */
    public function list($search = null, $size = null, $needDeleted = false, $needNewFirst = false): BotPageCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $botPages = BotPage::query();

        if ($needDeleted)
            $botPages = $botPages->withTrashed();

        $botPages = $botPages->where("bot_id", $this->bot->id);

        if (!is_null($search))
            $botPages = $botPages
                ->where(function ($q) use ($search) {
                    $q->whereHas("slug", function ($q) use ($search) {
                        $q->where("command", 'like', "%$search%");
                    })
                        ->orWhere("content", 'like', "%$search%");

                })
                ->orWhere("id", 'like', "%$search%");

        $botPages = $botPages->orderBy("updated_at", $needNewFirst? "DESC":"ASC");

        return new BotPageCollection($botPages->paginate($size));
    }

    /**
     * @throws HttpException
     */
    public function duplicate($pageId): BotPageResource
    {
        $botPage = BotPage::query()
            ->with(["slug"])
            ->where("id", $pageId)
            ->first();

        if (is_null($botPage))
            throw new HttpException(404, "Страница не найдена!");

        $newBotPage = $botPage->replicate();

        $slug = $newBotPage->slug->replicate();
        $slug->slug = Str::uuid();
        $slug->command = "[Копия]" . $slug->command;
        $slug->save();

        $newBotPage->bot_menu_slug_id = $slug->id;
        $newBotPage->save();


        return new BotPageResource($newBotPage);
    }

    /**
     * @throws HttpException
     */
    public function destroy($pageId, $force = false): BotPageResource
    {

        $botPage = !$force ?
            BotPage::query()->where("id", $pageId)
                ->first() :
            BotPage::query()->withTrashed()->where("id", $pageId)
                ->first();

        if (is_null($botPage))
            throw new HttpException(404, "Страница не найдена!");

        if ($force) {
            $slug = BotMenuSlug::query()
                ->find($botPage->bot_menu_slug_id);

            if (!is_null($slug))
                $slug->delete();

            $botPage->reply_keyboard_id = null;
            $botPage->inline_keyboard_id = null;
            $botPage->next_page_id = null;
            $botPage->next_bot_dialog_command_id = null;
            $botPage->next_bot_menu_slug_id = null;
            $botPage->rules_else_page_id = null;
           // $botPage->bot_id = null;
            $botPage->save();
            $tmp = $botPage;
            $botPage->forceDelete();
            return new BotPageResource($tmp);
        }

        $tmp = $botPage;
        $botPage->delete();

        return new BotPageResource($tmp);
    }


    /**
     * @throws HttpException
     */
    public function restore($pageId): BotPageResource
    {
        $botPage = BotPage::query()
            ->withTrashed()
            ->where("id", $pageId)
            ->first();

        if (is_null($botPage))
            throw new HttpException(404, "Страница не найдена!");

        $botPage->deleted_at = null;
        $botPage->save();

        return new BotPageResource($botPage);
    }

    /**
     * @param array{content: string, command: string, comment: string, bot_id: int} $pageData
     * @throws HttpException
     * @throws ValidationException
     */
    public function create(array $pageData, array $uploadedPhotos = null): BotPageResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($pageData, [
            // "content" => "required",
            "command" => "required",
            "comment" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $photos = $this->uploadPhotos("/public/companies/$company->slug", $uploadedPhotos);

        $tmp = (object)$pageData;
        unset($tmp->photos);
        $tmp->images = count($photos) == 0 ? null : $photos;

        $replyKeyboard = $tmp->reply_keyboard ?? null;
        $inlineKeyboard = $tmp->inline_keyboard ?? null;

        $tmp->reply_keyboard_id = null;
        $tmp->inline_keyboard_id = null;
        $tmp->videos = isset($pageData["videos"]) ? json_decode($pageData["videos"] ?? '[]') : null;
        $tmp->audios = isset($pageData["audios"]) ? json_decode($pageData["audios"] ?? '[]') : null;
        $tmp->documents = isset($pageData["documents"]) ? json_decode($pageData["documents"] ?? '[]') : null;

        $tmp->content = $tmp->content ?? "";
        $tmp->is_external = (bool)($tmp->is_external ?? false);
        $tmp->bot_id = $this->bot->id;

        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($tmp->reply_keyboard);

            unset($tmp->reply_keyboard);

            $strSlug = Str::uuid();
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "reply",
                'slug' => $strSlug,
                'menu' => $keyboard,
            ]);

            $tmp->reply_keyboard_id = $menu->id;
        }

        if (!is_null($inlineKeyboard)) {
            $keyboard = json_decode($tmp->inline_keyboard);

            unset($tmp->inline_keyboard);

            $strSlug = Str::uuid();
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "inline",
                'slug' => $strSlug,
                'menu' => $keyboard,
            ]);

            $tmp->inline_keyboard_id = $menu->id;
        }

        $oldSlugs = BotMenuSlug::query()
            ->where("bot_id", $this->bot->id)
            ->where("command", $tmp->command)
            ->whereNull("deprecated_at")
            ->get();

        if (!empty($oldSlugs)) {
            foreach ($oldSlugs as $oldSlug) {
                $oldSlug->deprecated_at = Carbon::now();
                $oldSlug->save();
            }
        }
        $strSlug = Str::uuid();
        $slug = BotMenuSlug::query()->create([
            'bot_id' => $this->bot->id,
            'command' => $tmp->command,
            'comment' => $tmp->comment,
            'slug' => $strSlug,
            'next_page_id' => $tmp->next_page_id,
        ]);

        $tmp->bot_menu_slug_id = $slug->id;
        unset($tmp->slug);
        unset($tmp->command);
        unset($tmp->comment);

        if (!is_null($tmp->rules_if ?? null))
            $tmp->rules_if = json_decode($tmp->rules_if);

        $page = BotPage::query()->create((array)$tmp);

        $this->bot->updated_at = Carbon::now();
        $this->bot->save();

        $page = BotPage::query()->find($page->id);

        return new BotPageResource($page);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function update(array $pageData, array $uploadedPhotos = null): BotPageResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($pageData, [
            "id" => "required",
            // "content" => "required",
            "command" => "required",
            "comment" => "required",
            "slug_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $page = BotPage::query()
            ->where("id", $pageData["id"])
            ->first();

        if (is_null($page))
            throw new HttpException(404, "Страница не найдена!");

        $photos = $this->uploadPhotos("/public/companies/$company->slug", $uploadedPhotos);

        $tmp = (object)$pageData;
        $tmp->videos = isset($pageData["videos"]) ? json_decode($pageData["videos"] ?? '[]') : null;
        $tmp->documents = isset($pageData["documents"]) ? json_decode($pageData["documents"] ?? '[]') : null;
        $tmp->audios = isset($pageData["audios"]) ? json_decode($pageData["audios"] ?? '[]') : null;
        $tmp->is_external = (bool)($tmp->is_external ?? false);
        $tmp->content = $tmp->content ?? "";

        unset($tmp->photos);

        $images = $tmp->images ?? null;

        if (!is_null($images))
            $images = json_decode($images);


        $tmp->images = count($photos) == 0 ? (is_array($images) ? $images : null) : [...$photos, ...$images];

        $replyKeyboard = $tmp->reply_keyboard ?? null;
        $inlineKeyboard = $tmp->inline_keyboard ?? null;

        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($tmp->reply_keyboard);
            unset($tmp->reply_keyboard);

            $reply_keyboard_id = $tmp->reply_keyboard_id ?? -1;
            $menu = BotMenuTemplate::query()
                ->where("bot_id", $this->bot->id)
                ->where("id", $reply_keyboard_id)
                ->first();

            if (!is_null($menu))
                $menu->update([
                    'menu' => $keyboard,
                ]);
            else {
                $strSlug = Str::uuid();
                $menu = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "reply",
                    'slug' => $strSlug,
                    'menu' => $keyboard,
                ]);
            }

            $tmp->reply_keyboard_id = $menu->id;
        }

        if (!is_null($inlineKeyboard)) {
            $keyboard = json_decode($tmp->inline_keyboard);

            unset($tmp->inline_keyboard);


            $inline_keyboard_id = $tmp->inline_keyboard_id ?? -1;

            $menu = BotMenuTemplate::query()->where("id", $inline_keyboard_id)
                ->first();

            if (!is_null($menu))
                $menu->update([
                    'menu' => $keyboard,
                ]);
            else {
                $strSlug = Str::uuid();
                $menu = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "inline",
                    'slug' => $strSlug,
                    'menu' => $keyboard,
                ]);
            }

            $tmp->inline_keyboard_id = $menu->id;
        }


        $slug = BotMenuSlug::query()
            ->where("id", $tmp->slug_id)
            ->first();

        if (!is_null($slug))
            $slug->update([
                'command' => $tmp->command,
                'comment' => $tmp->comment,
                'next_page_id' => $tmp->next_page_id ?? null,
            ]);

        unset($tmp->slug);
        unset($tmp->command);
        unset($tmp->comment);
        unset($tmp->slug_id);

        if (!is_null($tmp->rules_if ?? null))
            $tmp->rules_if = json_decode($tmp->rules_if);

        $page->update((array)$tmp);

        $this->bot->updated_at = Carbon::now();
        $this->bot->save();

        return new BotPageResource($page);
    }

}
