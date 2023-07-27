<?php

namespace App\Http\Controllers\Admin;

use App\Classes\SystemUtilitiesTrait;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotStoreRequest;
use App\Http\Requests\BotUpdateRequest;
use App\Http\Resources\BotMenuSlugResource;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotPageResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\BotUser;
use App\Models\Company;
use App\Models\ImageMenu;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function App\Http\Controllers\mb_strpos;

class BotController extends Controller
{

    use SystemUtilitiesTrait;

    public function sendCallback(Request $request)
    {
        $request->validate([
            "bot_domain" => "required",
            "slug_id" => "required",
            "telegram_chat_id" => "required",
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        $type = $request->type ?? null;
        $bot = \App\Models\Bot::query()
            ->with(["company"])
            ->where("bot_domain", $request->bot_domain)
            ->first();

        /*      $botUser = BotUser::query()
                  ->where("bot_id", $bot->id)
                  ->where("telegram_chat_id", $request->telegram_chat_id)
                  ->first();*/

        $slug = BotMenuSlug::query()
            ->where("id", $request->slug_id)
            ->first();

        $callbackChannel = $bot->main_channel ?? env("BASE_ADMIN_CHANNEL");

        $typeText = match ($type) {
            'booking' => "#бронированиестолика",
            default => "#обратнаясвязь",
        };

        $adminMessage = "$typeText\nБот: %s\nСкрипт: #%s (название скрипта: %s) \nПользователь: \n -tg id: %s \n -имя: %s \n -телефон: %s)\nСообщение: %s\n";
        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($callbackChannel,
                sprintf($adminMessage,
                    $bot->bot_domain,
                    $slug->id,
                    $slug->slug,
                    $request->telegram_chat_id ?? '-',
                    $request->name ?? '-',
                    $request->phone ?? '-',
                    $request->message ?? '-'
                ));

        return response()->noContent();
    }

    public function getSelf(Request $request)
    {
        $request->validate([
            "telegram_chat_id" => "required",
            "bot_id" => "required"
        ]);

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $request->telegram_chat_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        return new BotUserResource($botUser);
    }

    public function requestTelegramChannel(Request $request)
    {
        $request->validate([
            "token" => "required",
            "channel" => "required",
        ]);

        $token = $request->token;
        $channel = $request->channel;

        $res = Http::get("https://api.telegram.org/bot$token/sendMessage?chat_id=$channel&text=channelId");

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
            ->where("is_global", true)
            ->whereNull("bot_id")
            ->get()
            ->unique("slug");

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
            ->where("is_template", true)
            ->select("bot_domain", "id", "template_description")
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

    public function loadPages(Request $request, $botId)
    {
        $pages = BotPage::query()
            ->where("bot_id", $botId)
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json(BotPageResource::collection($pages));
    }

    public function loadSlugs(Request $request, $botId)
    {
        $isGlobal = $request->isGlobal == "true" ? true : false;


        $slugs = BotMenuSlug::query()
            ->where("bot_id", $botId)
            ->where("is_global", $isGlobal)
            ->orderBy("created_at", "desc")
            ->get();

        //dd($slugs->toArray());

        return response()->json(BotMenuSlugResource::collection($slugs));
    }

    public function index(Request $request)
    {
        $companyId = $request->companyId ?? null;

        $size = $request->get("size") ?? config('app.results_per_page');
        $search = $request->search ?? null;

        $bots = Bot::query()
            ->withTrashed();

        if (!is_null($companyId))
            $bots = $bots->where("company_id", $request->companyId);

        if (!is_null($search))
            $bots = $bots->where("bot_domain", 'like', "%$search%");

        $bots = $bots
            ->orderBy("updated_at", 'DESC')
            ->paginate($size);


        return BotResource::collection($bots);
    }


    public function destroy(Request $request, $botId)
    {
        $bot = Bot::query()->find($botId);

        if (is_null($bot))
            return response()->noContent(404);

        $bot->deleted_at = Carbon::now();
        $bot->save();

        return response()->noContent();
    }

    public function restore(Request $request, $botId)
    {
        $bot = Bot::query()
            ->withTrashed()
            ->find($botId);

        if (is_null($bot))
            return response()->noContent(404);

        $bot->deleted_at = null;
        $bot->save();

        return response()->noContent();
    }

    public function createKeyboardTemplate(Request $request)
    {
        $request->validate([
            "slug" => "required",
            "menu" => "required",
            "type" => "required",
            "bot_id" => "required",
        ]);

        $botMenuTemplate = BotMenuTemplate::query()
            ->create([
                "slug" => $request->slug ?? Str::uuid(),
                "menu" => json_decode($request->menu),
                "type" => $request->type,
                "bot_id" => $request->bot_id,
            ]);

        return \response()->json(new BotMenuTemplateResource($botMenuTemplate));
    }

    public function editKeyboardTemplate(Request $request)
    {
        $request->validate([
            "id" => "required",
            "slug" => "required",
            "menu" => "required",
            "type" => "required",
            "bot_id" => "required",
        ]);


        $botMenuTemplate = BotMenuTemplate::query()->find($request->id);

        if (is_null($botMenuTemplate))
            return response()->noContent(404);


        $botMenuTemplate
            ->update([
                "slug" => $request->slug ?? Str::uuid(),
                "menu" => json_decode($request->menu),
                "type" => $request->type,
                "bot_id" => $request->bot_id,
            ]);

        return \response()->json(new BotMenuTemplateResource($botMenuTemplate));
    }

    public function createLocation(Request $request): Response
    {


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

    public function createBotLazy(Request $request)
    {

        $services = [
            "investors" => [""],
            "franchise" => [""],
            "cashback" => ["", "", ""],
            "agent-cabinet" => [""],
            "referral-bonus" => [""],
            "event-form" => [""],
            "attached-documents" => [""],
            "lead-magnet" => [""],
            "sales-funnel" => [""],
            "reviews" => [""],
            "ask-a-question" => [""],
            "online-consultation" => [""],
            "location" => [""],
            "promotions" => [""],
            "our-clients" => [""],
            "cost-of-services" => [""],
            "custom-shop" => [""],
            "buy-or-try" => [""],
            "delivery" => [""],
            "booking" => [""],
            "atmosphere" => [""],
            "courses" => [""],
            "individual-button" => [""],
        ];

        $name = $request->name;
        $token = $request->token ?? null;
        $botDomain = $request->botDomain;

        $greeting = json_decode($request->greeting);
        $contacts = json_decode($request->contacts);
        $selfInfo = json_decode($request->selfInfo);
        $businessInfo = json_decode($request->businessInfo);
        $functions = json_decode($request->functions ?? '[]');

        $tmpLinks = Collection::make($contacts->links)
            ->where("slug", "social-link")
            ->all();

        $links = [];

        foreach ($tmpLinks as $link)
            $links[] = (object)[
                "title" => $link->description,
                "url" => $link->value,
            ];

        $phones = Collection::make($contacts->links)
            ->where("slug", "phone-number")
            ->pluck("value")
            ->all();

        $email = Collection::make($contacts->links)
            ->where("slug", "email")
            ->first();

        $address = Collection::make($contacts->links)
            ->where("slug", "address")
            ->first();

        $photos = [];


        dd($phones);

        $company = Company::query()->create([
            'title' => $businessInfo->name,
            'slug' => $botDomain,
            'description' => $businessInfo->text,
            'image' => null,
            'address' => $address->value,
            'phones' => $phones,
            'links' => $links,
            'email' => $email->value ?? null,
            'schedule' => [],
            'manager' => $selfInfo->name,
            'is_active' => true,
            'creator_id' => null,
            'owner_id' => null,
            'blocked_message' => null,
            'blocked_at' => null,
        ]);


        $greeting_image_avatar = $greeting->need_photo ?
            $this->file($request, $company->slug, "greeting_image_avatar") :
            ($greeting->avatar ?? null);

        $greeting_image_profile = $greeting->need_photo ?
            $this->file($request, $company->slug, "greeting_image_profile") :
            ($greeting->profile ?? null);

        $company->image = $greeting_image_profile;
        $company->save();

        $contacts_image = $contacts->need_photo ?
            $this->file($request, $company->slug, "contacts_image") :
            ($contacts->image ?? null);

        $self_info_image = $selfInfo->need_photo ?
            $this->file($request, $company->slug, "self_info_image") :
            ($selfInfo->image ?? null);

        $business_info_image = $businessInfo->need_photo ?
            $this->file($request, $company->slug, "business_info_image") :
            ($businessInfo->image ?? null);

        $botType = BotType::query()->where("slug", "business_card")->first();

        $bot = Bot::query()->create([
            'company_id' => $company->id,
            'welcome_message' => $greeting->text,
            'bot_domain' => $botDomain,
            'bot_token' => $token ?? "test_replacement_token",
            'bot_token_dev' => $token ?? "test_replacement_token",
            'order_channel' => -1,
            'main_channel' => -1,
            'balance' => 3000,
            'tax_per_day' => 10,
            'image' => $greeting_image_avatar,
            'description' => $businessInfo->text,
            'info_link' => null,
            'social_links' => $links,
            'is_active' => true,
            'maintenance_message' => "Техническое обслуживание",
            'bot_type_id' => $botType->id,
            'level_1' => 7,
            'level_2' => 3,
            'level_3' => 1,
            'is_template' => false,
            'template_description' => "Не является шаблоном",
        ]);

        if (!is_null($token))
            BotManager::bot()->setWebhooks();

        return new BotResource($bot);
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
            // "selected_bot_template_id" => "required",
            //"slugs" => "required",
            //"pages" => "required",
            //"keyboards" => "required",
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
        $tmp->is_template = $request->is_template ?? true;

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $keyboards = null;
        if (isset($request->keyboards)) {
            $keyboards = json_decode($request->keyboards);
            unset($tmp->keyboards);
        }
        $slugs = null;

        if (isset($request->slugs)) {
            $slugs = json_decode($request->slugs);
            unset($tmp->slugs);
        }

        $pages = null;

        if (isset($request->pages)) {
            $pages = json_decode($request->pages);
            unset($tmp->pages);
        }

        if (!is_null($tmp->selected_bot_template_id))
            unset($tmp->selected_bot_template_id);

        //dd($tmp);
        $bot = Bot::query()->create((array)$tmp);

        if (!is_null($pages))
            foreach ($pages as $page) {
                $page = (object)$page;

                $tmpSlug = BotMenuSlug::query()->find($page->bot_menu_slug_id);

                if (!is_null($tmpSlug)) {
                    $tmpSlug = $tmpSlug->replicate();
                    $tmpSlug->bot_id = $bot->id;
                    $tmpSlug->save();

                    BotPage::query()->create([
                        'bot_menu_slug_id' => $tmpSlug->id,
                        'content' => $page->content,
                        'images' => $page->images,
                        'reply_keyboard_id' => $page->reply_keyboard_id,
                        'inline_keyboard_id' => $page->inline_keyboard_id,
                        'bot_id' => $bot->id,
                    ]);
                }
            }


        if (!is_null($slugs))
            foreach ($slugs as $slug)
                BotMenuSlug::query()->create([
                    'bot_id' => $bot->id,
                    'command' => $slug->command,
                    'comment' => $slug->comment,
                    'slug' => $slug->slug,
                ]);

        if (!is_null($keyboards))
            foreach ($keyboards as $keyboard)
                BotMenuTemplate::query()->create([
                    'bot_id' => $bot->id,
                    'type' => $keyboard->type,
                    'slug' => $keyboard->slug,
                    'menu' => $keyboard->menu,
                ]);

        BotManager::bot()->setWebhooks();

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
            // "slugs" => "required",


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
                if (!is_null($keyboard)) {

                    $tmpPages = BotPage::query()
                        ->where("reply_keyboard_id", $keyboard->id)
                        ->orWhere('inline_keyboard_id', $keyboard->id)
                        ->get();

                    if (!empty($tmpPages))
                        foreach ($tmpPages as $tmpPage) {
                            if ($tmpPage->reply_keyboard_id == $keyboard->id)
                                $tmpPage->reply_keyboard_id = null;

                            if ($tmpPage->inline_keyboard_id == $keyboard->id)
                                $tmpPage->inline_keyboard_id = null;

                            $tmpPage->save();

                        }
                    $keyboard->delete();

                }

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
                $photos[] = $imageName;
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
        $tmp->is_template = $request->is_template ?? true;

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $keyboards = null;
        if (isset($request->keyboards)) {
            $keyboards = json_decode($request->keyboards);
            unset($tmp->keyboards);
        }

        $slugs = null;

        if (isset($request->slugs)) {
            $slugs = json_decode($request->slugs);
            unset($tmp->slugs);
        }
        unset($tmp->selected_bot_template_id);

        //dd($tmp);

        $bot->update((array)$tmp);

        if (!is_null($slugs))
            foreach ($slugs as $slug) {
                $slugId = $slug->id ?? null;

                $tmpSlug = !is_null($slugId) ? BotMenuSlug::query()
                    ->where("id", $slug->id)
                    ->where("command", $slug->command)
                    ->where("slug", $slug->slug)
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


        if (!is_null($keyboards))
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

        BotManager::bot()->setWebhooks();

        return new BotResource($bot);
    }

    public function removeKeyboardTemplate(Request $request, $templateId)
    {
        $botMenuTemplate = BotMenuTemplate::query()->find($templateId);

        if (is_null($botMenuTemplate))
            return response()->noContent(404);

        $pages = BotPage::query()
            ->where('reply_keyboard_id', $templateId)
            ->orWhere('inline_keyboard_id', $templateId)
            ->get();


        foreach ($pages as $page) {
            if ($page->reply_keyboard_id == $templateId)
                $page->reply_keyboard_id = null;
            if ($page->inline_keyboard_id == $templateId)
                $page->inline_keyboard_id = null;
            $page->save();
        }

        $botMenuTemplate->delete();

        return \response()->noContent();
    }


}
