<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotStoreRequest;
use App\Http\Requests\BotUpdateRequest;
use App\Http\Resources\BotMenuSlugResource;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotType;
use App\Models\BotUser;
use App\Models\Company;
use App\Models\ImageMenu;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use function App\Http\Controllers\mb_strpos;

class BotController extends Controller
{

    public function requestTelegramChannel(Request $request)
    {
        $request->validate([
            "token" => "required",
            "channel" => "required",
        ]);

        $token = $request->token;
        $channel = $request->channel;

        $res = Http::get("https://api.telegram.org/bot$token/sendMessage?chat_id=$channel&text=test");

        return \response()->json($res->json());
    }

    public function getCurrentBotUser(Request $request)
    {
        $request->validate([
            "tg" => "required",
            "bot_id" => "required"
        ]);


        $botUser = BotUser::query()
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        return new BotUserResource($botUser);

    }

    public function loadDescriptions()
    {
        $bots = Bot::query()
            ->select("welcome_message", "maintenance_message", "description")
            ->get();

        $tmp = [];

        foreach ($bots as $bot) {
            if (!empty($bot->welcome_message))
                $tmp[] = (object)[
                    "text" => $bot->welcome_message
                ];
            if (!empty($bot->maintenance_message))
                $tmp[] = (object)[
                    "text" => $bot->maintenance_message
                ];
            if (!empty($bot->description))
                $tmp[] = (object)[
                    "text" => $bot->description
                ];
        }

        return response()->json([
            "data" => $tmp
        ]);
    }

    public function loadAllSlugs(Request $request)
    {
        $slugs = BotMenuSlug::query()
            ->get()->unique("slug");

        return BotMenuSlugResource::collection($slugs);
    }

    public function changeUserStatus(Request $request)
    {
        $request->validate([
            "botUserId" => "required",
            "status" => "required"
        ]);

        $botUser = BotUser::query()
            ->where("id", $request->botUserId)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $botUser->is_admin = $request->status == 1;
        $botUser->save();

        $status = $botUser->is_admin ? "Администратор" : "Пользователь";
        BotMethods::bot()
            ->whereId($botUser->bot_id)
            ->sendSlugKeyboard($botUser->telegram_chat_id,
                "Вам изменили статус учетной записи на \"$status\"",
                ($botUser->is_admin ? "main_menu_restaurant_3" : "main_menu_restaurant_2")
            );

        return response()->noContent();
    }

    public function loadBotUsers(Request $request)
    {
        $request->validate([
            "botId" => "required"
        ]);

        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;

        $botUsers = BotUser::query();

        if (!is_null($search)) {
            $botUsers = $botUsers
                ->where("name", 'like', "%$search%")
                ->orWhere("phone", 'like', "%$search%")
                ->orWhere("email", 'like', "%$search%")
                ->orWhere("fio_from_telegram", 'like', "%$search%");
        }

        $botUsers = $botUsers->where("bot_id", $request->botId)
            ->paginate($size);

        return BotUserResource::collection($botUsers);
    }

    public function loadBotsAsTemplate(Request $request)
    {
        $bots = Bot::query()
            ->select("bot_domain", "id")
            ->get();

        return response()->json($bots->toArray());
    }

    public function loadLocations(Request $request, $companyId)
    {
        $locations = Location::query()
            ->where("company_id", $companyId)
            ->get();

        return LocationResource::collection($locations);
    }

    public function loadImageMenu(Request $request, $botId)
    {
        $menus = ImageMenu::query()
            ->where("bot_id", $botId)
            ->get();

        return ImageMenuResource::collection($menus);
    }

    public function loadKeyboardsByText(Request $request, $botId)
    {
        $request->validate([
            "text" => "required"
        ]);

        $text = $request->text ?? '';

        $keyboards = BotMenuTemplate::query()
            ->where("bot_id", 1)
            ->get();

        $tmp = [];
        foreach ($keyboards as $keyboard) {
            $find = false;
            foreach ($keyboard->menu as $row) {
                foreach ($row as $button) {
                    $button = (object)$button;
                    if (mb_strpos($button->text, $text) !== false)
                        $find = true;
                }
            }

            if ($find)
                $tmp[] = $keyboard;

        }


        return response()->json(BotMenuTemplateResource::collection($tmp));
    }

    public function loadKeyboards(Request $request, $botId)
    {
        $keyboards = BotMenuTemplate::query()
            ->where("bot_id", $botId)
            ->get();

        return response()->json(BotMenuTemplateResource::collection($keyboards));
    }

    public function loadSlugs(Request $request, $botId)
    {
        $slugs = BotMenuSlug::query()
            ->with(["page"])
            ->where("bot_id", $botId)
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json(BotMenuSlugResource::collection($slugs));
    }

    public function index(Request $request)
    {
        $companyId = $request->companyId ?? null;

        $size = $request->get("size") ?? config('app.results_per_page');
        $search = $request->search ?? null;

        $bots = Bot::query();

        if (!is_null($companyId))
            $bots = $bots->where("company_id", $request->companyId);

        if (!is_null($search))
            $bots = $bots->where("bot_domain", 'like', "%$search%");

        $bots = $bots
            ->paginate($size);


        return BotResource::collection($bots);
    }

    public function store(BotStoreRequest $request): Response
    {
        $bot = Bot::create($request->validated());

        return new BotResource($bot);
    }

    public function show(Request $request, Bot $bot): Response
    {
        return new BotResource($bot);
    }

    public function update(BotUpdateRequest $request, Bot $bot): Response
    {
        $bot->update($request->validated());

        return new BotResource($bot);
    }

    public function destroy(Request $request, Bot $bot): Response
    {
        $bot->delete();

        return response()->noContent();
    }


    public function createLocation(Request $request): Response
    {
        $request->validate([
            "lat" => "required",
            "lon" => "required",
            "address" => "required",
            "description" => "required",
            "company_id" => "required"
        ]);


        $company = Company::query()->where("id", $request->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        if (isset($request->deleted_locations)) {

            $deleteds = json_decode($request->deleted_locations);

            foreach ($deleteds as $id) {
                $dlocation = Location::query()->find($id);
                if (!is_null($dlocation))
                    $dlocation->delete();
            }

        }

        $photos = [];

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $tmp = (object)$request->all();

        $tmp->images = json_decode($tmp->images ?? '[]');

        if (count($photos) > 0)
            $tmp->images = $photos;

        $tmp->is_active = true;
        $tmp->can_booking = $request->can_booking == "true" ? true : false;

        $locationId = $tmp->id ?? null;
        if (!is_null($locationId)) {
            $location = Location::query()
                ->where("id", $locationId)
                ->first();

            if (!is_null($location))
                $location->update((array)$tmp);
        } else {
            Location::query()->create((array)$tmp);
        }

        return response()->noContent();
    }

    public function createImageMenu(Request $request)
    {

        $request->validate([
            'title' => "required|string:255",
            'description' => "required|string:255",
            'bot_id' => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        if (is_null($bot))
            return response()->noContent(400);

        if (isset($request->deleted_menus)) {

            $deleteds = json_decode($request->deleted_menus);

            foreach ($deleteds as $id) {
                $dmenu = ImageMenu::query()->find($id);
                if (!is_null($dmenu))
                    $dmenu->delete();
            }

        }

        $imageName = $request->image ?? null;
        if ($request->hasFile('preview')) {

            $file = $request->file('preview');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $companySlug = $bot->company->slug;
            $file->storeAs("/public/companies/$companySlug/$imageName");

        }

        $tmp = (object)$request->all();

        $tmp->image = $imageName;
        $tmp->product_count = $request->product_count ?? 0;

        $menuId = $tmp->id ?? null;
        if (!is_null($menuId)) {
            $imgMenu = ImageMenu::query()->find($menuId);

            if (!is_null($imgMenu))
                $imgMenu->update((array)$tmp);

            $imgMenu = $imgMenu->refresh();
        } else
            $imgMenu = ImageMenu::query()->create((array)$tmp);

        return new ImageMenuResource($imgMenu);

    }

    public function createBot(Request $request)
    {
        $request->validate([
            "bot_domain" => "required|unique:bots,bot_domain",
            "bot_token" => "required",

            "balance" => "required",
            "tax_per_day" => "required",
            "description" => "required",

            "maintenance_message" => "required",
            "welcome_message" => "required",
            "level_1" => "required",
            "selected_bot_template_id" => "required",
            "slugs" => "required",
            "keyboards" => "required",
            "company_id" => "required",
        ]);


        $company = Company::query()->where("id", $request->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        $photos = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $botType = BotType::query()->where("slug", "cashback")->first();

        $tmp = (object)$request->all();

        $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);
        $tmp->level_2 = $request->level_2 ?? 0;
        $tmp->level_3 = $request->level_3 ?? 0;
        $tmp->bot_type_id = $botType->id;
        $tmp->is_active = true;

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $keyboards = json_decode($request->keyboards);
        unset($tmp->keyboards);
        $slugs = json_decode($request->slugs);
        unset($tmp->slugs);
        unset($tmp->selected_bot_template_id);

        //dd($tmp);
        $bot = Bot::query()->create((array)$tmp);

        foreach ($slugs as $slug)
            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => $slug->command,
                'comment' => $slug->comment,
                'slug' => $slug->slug,
            ]);

        foreach ($keyboards as $keyboard)
            BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => $keyboard->type,
                'slug' => $keyboard->slug,
                'menu' => $keyboard->menu,
            ]);

        return new BotResource($bot);
    }

    public function updateBot(Request $request)
    {
        $request->validate([
            "bot_domain" => "required",
            "bot_token" => "required",

            "balance" => "required",
            "tax_per_day" => "required",
            "description" => "required",

            "social_links" => "required",
            "maintenance_message" => "required",
            "welcome_message" => "required",
            "level_1" => "required",
            "slugs" => "required",
            "keyboards" => "required",

        ]);


        $bot = Bot::query()
            ->where("id", $request->id)
            ->first();

        if (is_null($bot))
            return response()->noContent(400);

        $company = Company::query()->where("id", $bot->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        if (isset($request->removed_keyboards)) {

            $tmpKeyboards = json_decode($request->removed_keyboards);

            foreach ($tmpKeyboards as $id) {
                $keyboard = BotMenuTemplate::query()->find($id);
                if (!is_null($keyboard))
                    $keyboard->delete();
            }

        }

        if (isset($request->removed_slugs)) {

            $tmpSlugs = json_decode($request->removed_slugs);

            foreach ($tmpSlugs as $id) {
                $slug = BotMenuSlug::query()
                    ->with(["page"])
                    ->find($id);
                if (!is_null($slug)) {
                    if (!is_null($slug->page)) {
                        $slug->page->delete();
                        $slug->delete();
                    } else
                        $slug->delete();
                }

            }

        }

        $photos = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $botType = BotType::query()->where("slug", "cashback")->first();

        $tmp = (object)$request->all();

        if (is_null($tmp->image))
            $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);
        $tmp->level_2 = $request->level_2 ?? 0;
        $tmp->level_3 = $request->level_3 ?? 0;
        $tmp->bot_type_id = $botType->id;
        $tmp->is_active = true;

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $keyboards = json_decode($request->keyboards);
        unset($tmp->keyboards);
        $slugs = json_decode($request->slugs);
        unset($tmp->slugs);
        unset($tmp->selected_bot_template_id);

        //dd($tmp);

        $bot->update((array)$tmp);

        foreach ($slugs as $slug) {
            $slugId = $slug->id ?? null;

            $tmpSlug = !is_null($slugId) ? BotMenuSlug::query()
                ->where("id", $slug->id)
                ->first() : null;

            if (!is_null($tmpSlug))
                $tmpSlug->update([
                    'command' => $slug->command,
                    'comment' => $slug->comment,
                    'slug' => $slug->slug,
                ]);
            else
                BotMenuSlug::query()->create([
                    'bot_id' => $request->id,
                    'command' => $slug->command,
                    'comment' => $slug->comment,
                    'slug' => $slug->slug,
                ]);
        }


        foreach ($keyboards as $keyboard) {

            $tmpKeyboard = BotMenuTemplate::query()
                ->where("id", $keyboard->id)
                ->first();

            if (!is_null($tmpKeyboard))
                $tmpKeyboard->update([
                    'type' => $keyboard->type,
                    'slug' => $keyboard->slug,
                    'menu' => $keyboard->menu,
                ]);
            else
                BotMenuTemplate::query()->create([
                    'bot_id' => $request->id,
                    'type' => $keyboard->type,
                    'slug' => $keyboard->slug,
                    'menu' => $keyboard->menu,
                ]);
        }
        $bot = Bot::query()->find($request->id);
        return new BotResource($bot);
    }

}
