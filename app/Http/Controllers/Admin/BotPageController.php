<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotPageStoreRequest;
use App\Http\Requests\BotPageUpdateRequest;
use App\Http\Resources\BotPageCollection;
use App\Http\Resources\BotPageResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BotPageController extends Controller
{
    public function index(Request $request)
    {
        $botId = $request->botId ?? null;

        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;

        $botPages = BotPage::query()
            ->where("bot_id", $botId);

        if (!is_null($search))
            $botPages = $botPages
                ->where(function ($q) use ($search) {
                    $q->whereHas("slug", function ($q) use ($search) {
                        $q->where("command", 'like', "%$search%");
                    })->orWhere("content", 'like', "%$search%");
                });


        $botPages = $botPages->paginate($size);

        return new BotPageCollection($botPages);
    }


    public function duplicate(Request $request, $pageId): Response
    {
        $botPage = BotPage::query()
            ->with(["slug"])
            ->where("id", $pageId)
            ->first();

        if (is_null($botPage))
            return \response()->noContent(404);


        $newBotPage = $botPage->replicate();

        $slug = $newBotPage->slug->replicate();
        $slug->slug = Str::uuid();
        $slug->save();

        $newBotPage->bot_menu_slug_id = $slug->id;
        $newBotPage->save();

        return response()->noContent();
    }

    public function destroy(Request $request, $pageId): Response
    {
        $botPage = BotPage::query()->where("id", $pageId)
            ->first();

        if (is_null($botPage))
            return \response()->noContent(404);

        $slug = BotMenuSlug::query()
            ->find($botPage->bot_menu_slug_id);

        if (!is_null($slug))
            $slug->delete();

        $botPage->reply_keyboard_id = null;
        $botPage->inline_keyboard_id = null;
        $botPage->next_page_id = null;
        $botPage->next_bot_dialog_command_id = null;
        $botPage->next_bot_menu_slug_id = null;
        $botPage->save();

        $botPage->forceDelete();

        return response()->noContent();
    }

    public function createPage(Request $request)
    {
        $request->validate([
            "content" => "required",
            "command" => "required",
            "comment" => "required",
            "bot_id" => "required",
            //"inline_keyboard"=>"required",
            //reply_keyboard"=>"required",
            //"images"=>"required",


        ]);


        $bot = Bot::query()->where("id", $request->bot_id)
            ->first();

        if (is_null($bot))
            return response()->noContent(400);

        $company = Company::query()
            ->where("id", $bot->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        $photos = [];

        if ($request->hasFile('photos')) {
            $files = $request->file('photos');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                $photos[] = $imageName;
            }


        }

        $tmp = (object)$request->all();
        unset($tmp->photos);
        $tmp->images = count($photos) == 0 ? null : $photos;

        // $text = str_replace(["<p>", "</p>"], "", $tmp->content);
        // $text = str_replace(["<br>", "<br/>"], "\n", $text);

        // $tmp->content = $text;

        $replyKeyboard = $request->reply_keyboard ?? null;
        $inlineKeyboard = $request->inline_keyboard ?? null;

        $tmp->reply_keyboard_id = null;
        $tmp->inline_keyboard_id = null;

        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($request->reply_keyboard);

            //  $keyboard = $this->keyboardAssign($keyboard, $bot->id);

            unset($tmp->reply_keyboard);

            $strSlug = Str::uuid();
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => "reply",
                'slug' => $strSlug,
                'menu' => $keyboard,
            ]);

            $tmp->reply_keyboard_id = $menu->id;
        }

        if (!is_null($inlineKeyboard)) {
            $keyboard = json_decode($request->inline_keyboard);

            // $keyboard = $this->keyboardAssign($keyboard, $bot->id);

            unset($tmp->inline_keyboard);

            $strSlug = Str::uuid();
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => "inline",
                'slug' => $strSlug,
                'menu' => $keyboard,
            ]);

            $tmp->inline_keyboard_id = $menu->id;
        }

        $oldSlugs = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("command", $request->command)
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
            'bot_id' => $bot->id,
            'command' => $request->command,
            'comment' => $request->comment,
            'slug' => $strSlug,
            'next_page_id' => $request->next_page_id,
        ]);

        $tmp->bot_menu_slug_id = $slug->id;
        unset($tmp->slug);
        unset($tmp->command);
        unset($tmp->comment);

        $tmp->rules_if = json_decode($tmp->rules_if);

        $page = BotPage::query()->create((array)$tmp);

        return new BotPageResource($page);
    }

    public function updatePage(Request $request)
    {
        $request->validate([
            "id" => "required",
            "content" => "required",
            "command" => "required",
            "comment" => "required",
            "bot_id" => "required",
            "slug_id" => "required",

        ]);


        $bot = Bot::query()->where("id", $request->bot_id)
            ->first();

        if (is_null($bot))
            return response()->noContent(400);

        $company = Company::query()
            ->where("id", $bot->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        $page = BotPage::query()
            ->where("id", $request->id)
            ->first();

        if (is_null($page))
            return response()->noContent(400);

        $photos = [];

        if ($request->hasFile('photos')) {
            $files = $request->file('photos');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                $photos[] = $imageName;
            }


        }

        $tmp = (object)$request->all();
        unset($tmp->photos);

        $images = $tmp->images ?? null;

        if (!is_null($images))
            $images = json_decode($images);


        $tmp->images = count($photos) == 0 ? (is_array($images) ? $images : null) : [...$photos, ...$images];


        //$text = str_replace(["<p>", "</p>"], "", $tmp->content);
        //$text = str_replace(["<br>", "<br/>"], "\n", $text);

        // $tmp->content = $text;

        $replyKeyboard = $request->reply_keyboard ?? null;
        $inlineKeyboard = $request->inline_keyboard ?? null;


        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($request->reply_keyboard);
            unset($tmp->reply_keyboard);

            //  $keyboard = $this->keyboardAssign($keyboard, $bot->id);

            $reply_keyboard_id = $tmp->reply_keyboard_id ?? -1;
            $menu = BotMenuTemplate::query()
                ->where("bot_id", $bot->id)
                ->where("id", $reply_keyboard_id)
                ->first();

            if (!is_null($menu))
                $menu->update([
                    'menu' => $keyboard,
                ]);
            else {
                $strSlug = Str::uuid();
                $menu = BotMenuTemplate::query()->create([
                    'bot_id' => $bot->id,
                    'type' => "reply",
                    'slug' => $strSlug,
                    'menu' => $keyboard,
                ]);
            }

            $tmp->reply_keyboard_id = $menu->id;
        }

        if (!is_null($inlineKeyboard)) {
            $keyboard = json_decode($request->inline_keyboard);

            unset($tmp->inline_keyboard);

            // $keyboard = $this->keyboardAssign($keyboard, $bot->id);

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
                    'bot_id' => $bot->id,
                    'type' => "inline",
                    'slug' => $strSlug,
                    'menu' => $keyboard,
                ]);
            }

            $tmp->inline_keyboard_id = $menu->id;
        }


        $slug = BotMenuSlug::query()
            ->where("id", $request->slug_id)
            ->first();

        if (!is_null($slug))
            $slug->update([
                'command' => $request->command,
                'comment' => $request->comment,
                'next_page_id' => $request->next_page_id ?? null,
            ]);

        unset($tmp->slug);
        unset($tmp->command);
        unset($tmp->comment);
        unset($tmp->slug_id);

        $tmp->rules_if = json_decode($tmp->rules_if);

        $page->update((array)$tmp);

        return new BotPageResource($page);
    }
}
