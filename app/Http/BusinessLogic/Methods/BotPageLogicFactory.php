<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BusinessLogic;
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


class BotPageLogicFactory extends BaseLogicFactory
{
    use LogicUtilities;

    /**
     * @throws HttpException
     */
    public function list($search = null, $size = null, $needDeleted = false, $needNewFirst = false, $order = "updated_at", $direction = "desc"): BotPageCollection
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

        $botPages = $botPages->orderBy($order, $direction);

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
     * @throws HttpException
     * @throws ValidationException
     */
    public function addPagesByKeyboard(array $pageData): void
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($pageData, [
            "keyboard" => "required",
            "settings" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $keyboard = $pageData["keyboard"];

        $pages = [];
        foreach ($keyboard as $row) {
            foreach ($row as $col) {
                $col = (array)$col;
                $pages[] = $col["text"] ?? $col;
            }

        }

        $strSlug = Str::uuid();
        $menu = BotMenuTemplate::query()->create([
            'bot_id' => $this->bot->id,
            'type' => "reply",
            'slug' => $strSlug,
            'menu' => $keyboard,
            'settings' => $pageData["settings"],
        ]);

        foreach ($pages as $pageName) {

            $findPage = !is_null(BotMenuSlug::query()
                ->where("bot_id", $this->bot->id)
                ->where("command", "$pageName")
                ->first());

            if ($findPage)
                continue;

            $strSlug = Str::uuid();
            $slug = BotMenuSlug::query()->create([
                'bot_id' => $this->bot->id,
                'command' => $pageName,
                'comment' => "Автоматически созданная страница $pageName",
                'slug' => $strSlug,
            ]);

            $tmp = [
                'bot_menu_slug_id' => $slug->id,
                'content' => "Контент страницы $pageName",
                'reply_keyboard_id' => $menu->id,
                'bot_id' => $this->bot->id,
            ];
            $page = BotPage::query()->create($tmp);
        }

        $this->bot->updated_at = Carbon::now();
        $this->bot->save();
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function addPages(array $pageData): void
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($pageData, [
            "pages" => "required|array",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $company = Company::query()
            ->where("id", $this->bot->company_id)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $keyboard = [];

        $index = 0;
        $row = [];
        foreach ($pageData["pages"] as $pageName) {
            $row[] = ["text" => $pageName];
            $index++;
            if ($index == 2) {
                $keyboard[] = $row;
                $row = [];
                $index = 0;
            }
        }

        if (count($row) > 0)
            $keyboard[] = $row;

        $strSlug = Str::uuid();
        $menu = BotMenuTemplate::query()->create([
            'bot_id' => $this->bot->id,
            'type' => "reply",
            'slug' => $strSlug,
            'menu' => $keyboard,
            'settings' => [
                "resize_keyboard" => true,
                "one_time_keyboard" => false,
                "input_field_placeholder" => "Главное меню",
                "is_persistent" => true,
            ],
        ]);

        foreach ($pageData["pages"] as $pageName) {

            $findPage = !is_null(BotMenuSlug::query()
                ->where("bot_id", $this->bot->id)
                ->where("command", "$pageName")
                ->first());

            if ($findPage)
                continue;

            $strSlug = Str::uuid();
            $slug = BotMenuSlug::query()->create([
                'bot_id' => $this->bot->id,
                'command' => $pageName,
                'comment' => "Автоматически созданная страница $pageName",
                'slug' => $strSlug,
            ]);

            $tmp = [
                'bot_menu_slug_id' => $slug->id,
                'content' => "Контент страницы $pageName",
                'reply_keyboard_id' => $menu->id,
                'bot_id' => $this->bot->id,
            ];
            $page = BotPage::query()->create($tmp);
        }

        $this->bot->updated_at = Carbon::now();
        $this->bot->save();
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

        $needPageCreateFromKeyboard = $pageData["need_page_create_from_keyboard"] ?? false;

        $tmp = (object)$pageData;
        unset($tmp->photos);

        if ($needPageCreateFromKeyboard)
            unset($tmp->need_page_create_from_keyboard);

        $tmp->images = count($photos) == 0 ? null : $photos;

        $replyKeyboard = $tmp->reply_keyboard ?? null;

        $inlineKeyboard = $tmp->inline_keyboard ?? null;

        $tmp->reply_keyboard_id = null;
        $tmp->inline_keyboard_id = null;
        $tmp->videos = isset($pageData["videos"]) ? json_decode($pageData["videos"] ?? '[]') : null;
        $tmp->audios = isset($pageData["audios"]) ? json_decode($pageData["audios"] ?? '[]') : null;
        $tmp->cashback_config = isset($pageData["cashback_config"]) ? json_decode($pageData["cashback_config"] ?? '[]') : null;
        $tmp->documents = isset($pageData["documents"]) ? json_decode($pageData["documents"] ?? '[]') : null;

        $tmp->content = $tmp->content ?? "";
        $tmp->is_external = (bool)($tmp->is_external ?? false);
        $tmp->need_log_user_action = (bool)($tmp->need_log_user_action ?? false);
        $tmp->bot_id = $this->bot->id;

        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($tmp->reply_keyboard);

            $replyKeyboardSettings = $keyboard->settings ?? null;
            $replyKeyboardMenu =  $keyboard->menu ?? null;

            $keyboard = $this->recursiveMenuFix($replyKeyboardMenu);

            $replyKeyboardSettings = (array)[
                "resize_keyboard" => ($replyKeyboardSettings->resize_keyboard ?? "true") == "true",
                "one_time_keyboard" => ($replyKeyboardSettings->one_time_keyboard ?? "true") == "true",
                "input_field_placeholder" => $replyKeyboardSettings->input_field_placeholder ?? null,
                "is_persistent" => ($replyKeyboardSettings->is_persistent ?? "true") == "true",
            ];


            unset($tmp->reply_keyboard);

            $strSlug = Str::uuid();
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $this->bot->id,
                'type' => "reply",
                'slug' => $strSlug,
                'menu' => $keyboard,
                'settings' => $replyKeyboardSettings,
            ]);

            $tmp->reply_keyboard_id = $menu->id;

            if ($needPageCreateFromKeyboard) {
                $this->addPagesByKeyboard(
                    [
                        "keyboard" => $keyboard,
                        "settings" => $replyKeyboardSettings,
                    ]
                );
            }

        }

        if (!is_null($inlineKeyboard)) {
            $keyboard = json_decode($tmp->inline_keyboard);

            $keyboard = $this->recursiveMenuFix($keyboard);

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
            //  'next_page_id' => $tmp->next_page_id,
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
        $tmp->cashback_config = isset($pageData["cashback_config"]) ? json_decode($pageData["cashback_config"] ?? '[]') : null;
        $tmp->is_external = (bool)($tmp->is_external ?? false);
        $tmp->need_log_user_action = (bool)($tmp->need_log_user_action ?? false);
        $tmp->content = $tmp->content ?? "";

        $needPageCreateFromKeyboard = $pageData["need_page_create_from_keyboard"] ?? false;

        unset($tmp->photos);

        if ($needPageCreateFromKeyboard)
            unset($tmp->need_page_create_from_keyboard);

        $images = $tmp->images ?? null;

        if (!is_null($images))
            $images = json_decode($images);


        $tmp->images = count($photos) == 0 ? (is_array($images) ? $images : null) : [...$photos, ...$images];

        $replyKeyboard = $tmp->reply_keyboard ?? null;
        $inlineKeyboard = $tmp->inline_keyboard ?? null;


        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($tmp->reply_keyboard);

            $replyKeyboardSettings = $keyboard->settings ?? null;
            $replyKeyboardMenu =  $keyboard->menu ?? null;


            $keyboard = $this->recursiveMenuFix($replyKeyboardMenu);

            unset($tmp->reply_keyboard);

            $reply_keyboard_id = $tmp->reply_keyboard_id ?? -1;
            $menu = BotMenuTemplate::query()
                ->where("bot_id", $this->bot->id)
                ->where("id", $reply_keyboard_id)
                ->first();

            $replyKeyboardSettings = (array)[
                "resize_keyboard" => ($replyKeyboardSettings->resize_keyboard ?? "true") == "true",
                "one_time_keyboard" => ($replyKeyboardSettings->one_time_keyboard ?? "true") == "true",
                "input_field_placeholder" => $replyKeyboardSettings->input_field_placeholder ?? null,
                "is_persistent" => ($replyKeyboardSettings->is_persistent ?? "true") == "true",
            ];



            if (!is_null($menu))
                $menu->update([
                    'menu' => $keyboard,
                    'settings' => $replyKeyboardSettings,
                ]);
            else {
                $strSlug = Str::uuid();
                $menu = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => "reply",
                    'slug' => $strSlug,
                    'menu' => $keyboard,
                    'settings' => $replyKeyboardSettings,
                ]);


            }

            $tmp->reply_keyboard_id = $menu->id;

            if ($needPageCreateFromKeyboard) {
                $this->addPagesByKeyboard(
                    [
                        "keyboard" => $keyboard,
                        "settings" => $replyKeyboardSettings,
                    ]
                );
            }
        }

        if (!is_null($inlineKeyboard)) {
            $keyboard = json_decode($tmp->inline_keyboard);

            $keyboard = $this->recursiveMenuFix($keyboard);

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

        $page->refresh();

        return new BotPageResource($page);
    }

}
