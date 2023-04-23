<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotPageStoreRequest;
use App\Http\Requests\BotPageUpdateRequest;
use App\Http\Resources\BotPageCollection;
use App\Http\Resources\BotPageResource;
use App\Http\Resources\BotResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BotPageController extends Controller
{
    public function index(Request $request)
    {
        $botId = $request->botId ?? null;

        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;

        $botPages = BotPage::query();

        if (!is_null($botId))
            $botPages = $botPages->where("bot_id", $botId);

        if (!is_null($search))
            $botPages = $botPages
                ->whereHas("slug", function ($q) use ($search) {
                    $q->where("command", 'like', "%$search%");
                })
                ->orWhere("content", 'like', "%$search%");

        $botPages = $botPages->paginate($size);

        return new BotPageCollection($botPages);
    }

    public function store(BotPageStoreRequest $request): Response
    {
        $botPage = BotPage::create($request->validated());

        return new BotPageResource($botPage);
    }

    public function show(Request $request, BotPage $botPage): Response
    {
        return new BotPageResource($botPage);
    }

    public function update(BotPageUpdateRequest $request, BotPage $botPage): Response
    {
        $botPage->update($request->validated());

        return new BotPageResource($botPage);
    }

    public function destroy(Request $request, $pageId): Response
    {
        $botPage = BotPage::query()->where("id", $pageId)
            ->first();

        if (is_null($botPage))
            return \response()->noContent(404);

        $botPage->delete();

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

        $text = str_replace(["<p>", "</p>"], "", $tmp->content);
        $text = str_replace(["<br>", "<br/>"], "\n", $text);

        $tmp->content = $text;

        $replyKeyboard = $request->reply_keyboard ?? null;
        $inlineKeyboard = $request->inline_keyboard ?? null;

        $tmp->reply_keyboard_id = null;
        $tmp->inline_keyboard_id = null;

        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($request->reply_keyboard);
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

        $strSlug = Str::uuid();
        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => $request->command,
            'comment' => $request->comment,
            'slug' => $strSlug,
        ]);

        $tmp->bot_menu_slug_id = $slug->id;
        unset($tmp->slug);
        unset($tmp->command);
        unset($tmp->comment);


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
            "reply_keyboard_id" => "required",
            "inline_keyboard_id" => "required",

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


        $tmp->images = count($photos) == 0 ? ($tmp->images ?? null) : $photos;

        $text = str_replace(["<p>", "</p>"], "", $tmp->content);
        $text = str_replace(["<br>", "<br/>"], "\n", $text);

        $tmp->content = $text;

        $replyKeyboard = $request->reply_keyboard ?? null;
        $inlineKeyboard = $request->inline_keyboard ?? null;


        if (!is_null($replyKeyboard)) {
            $keyboard = json_decode($request->reply_keyboard);
            unset($tmp->reply_keyboard);

            $menu = BotMenuTemplate::query()->where("id", $tmp->reply_keyboard_id)
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

            $menu = BotMenuTemplate::query()->where("id", $tmp->inline_keyboard_id)
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
            ]);

        unset($tmp->slug);
        unset($tmp->command);
        unset($tmp->comment);
        unset($tmp->slug_id);


        $page->update((array)$tmp);

        return new BotPageResource($page);
    }
}
