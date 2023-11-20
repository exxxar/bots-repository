<?php

namespace App\Http\Controllers\Bots\Web;


use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class BotController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function switchBotStatusManager(Request $request): Response
    {
        $request->validate([
            "botId" => "required"
        ]);

        BusinessLogic::manager()
            ->setBotUser($request->botUser ?? null)
            ->switchBotStatusManager($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function switchBotStatus(Request $request): Response
    {
        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->switchBotStatus();

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function updateShopLink(Request $request): BotResource
    {
        $request->validate([
            "vk_shop_link" => "required"
        ]);

        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->updateShopLink($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function sendToChannel(Request $request): Response
    {
        $request->validate([
            "text" => "required",
            "inline_keyboard" => "",
            "channel" => "required",

        ]);

        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->sendToChannel($request->all(),
                $request->hasFile('photos') ? $request->file('photos') : null
            );
        return response()->noContent();

    }

    /**
     * @throws ValidationException
     */
    public function sendCallback(Request $request): Response
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->sendCallback($request->all());

        return response()->noContent();
    }

    public function getSelf(Request $request): BotUserResource
    {
        return new BotUserResource($request->botUser);
    }

    public function getBot(Request $request): BotResource
    {
        return new BotResource($request->bot);
    }

    /**
     * @throws ValidationException
     */
    public function getManagerBot(Request $request): BotResource|Response
    {
        $request->validate([
            "botId" => "required"
        ]);

        return BusinessLogic::bots()
            ->setBotUser($request->botUser ?? null)
            ->getManagerBot($request->all());

    }


    public function requestManagerNotes(Request $request){
        return response()
            ->json(
                BusinessLogic::bots()
                    ->setBotUser($request->botUser ?? null)
                    ->setBot($request->bot ?? null)
                    ->notes()
            );
    }
    /**
     * @throws ValidationException
     */
    public function requestTelegramChannel(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "channel" => "required",
        ]);

        return response()
            ->json(
                BusinessLogic::bots()
                    ->setBot($request->bot ?? null)
                    ->requestTelegramChannel($request->all())
            );
    }


    public function loadDescriptions(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "data" => BusinessLogic::bots()
                ->descriptions()
        ]);
    }

    public function loadAllSlugs(Request $request): \App\Http\Resources\BotMenuSlugCollection
    {
        return BusinessLogic::slugs()
            ->setBot($request->bot ?? null)
            ->globals();
    }

    /**
     * @throws ValidationException
     */
    public function changeUserStatus(Request $request): Response
    {
        $request->validate([
            "botUserId" => "required",
            "status" => "required"
        ]);

        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->changeUserStatus($request->all());

        return response()->noContent();
    }

    public function loadBotUsers(Request $request): \App\Http\Resources\BotUserCollection
    {
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->list();
    }

    public function loadBotsAsTemplate(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(BusinessLogic::bots()
            ->templateList());
    }

    public function loadLocations(Request $request, $companyId): \App\Http\Resources\LocationCollection
    {
        return BusinessLogic::companies()
            ->locationsList($companyId);
    }

    public function loadImageMenu(Request $request): \App\Http\Resources\ImageMenuCollection
    {


        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->imageMenuList();
    }


    public function loadKeyboards(Request $request): \App\Http\Resources\BotMenuTemplateCollection
    {

        return BusinessLogic::keyboards()
            ->setBot($request->bot ?? null)
            ->list();
    }

    public function loadPages(Request $request): \App\Http\Resources\BotPageCollection
    {
        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
            ->list(
                null,
                $request->get("size") ?? config('app.results_per_page'),
            );
    }

    public function loadSlugs(Request $request): \App\Http\Resources\BotMenuSlugCollection
    {

        return BusinessLogic::slugs()
            ->setBot($request->bot ?? null)
            ->list(
                null,
                $request->get("size") ?? config('app.results_per_page'),
                ($request->isGlobal ?? false) == "true"
            );
    }

    public function index(Request $request): \App\Http\Resources\BotCollection
    {

        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->list(
                $request->companyId ?? null,
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function simpleList(Request $request): \App\Http\Resources\BotSecurityCollection
    {

        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->simple(
                $request->companyId ?? null,
                $request->search ?? null,
                $request->needSelfBots ?? false,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    /**
     * @throws ValidationException
     */
    public function duplicate(Request $request): BotResource
    {
        $request->validate([
            "company_id" => "required",
        ]);

        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->duplicate($request->company_id ?? null);
    }


    public function forceDelete(Request $request): Response
    {
        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->forceDelete();

        return response()->noContent();
    }

    public function destroy(Request $request): BotResource
    {
        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->destroy();

    }

    public function destroyByManager(Request $request, $botId): BotResource
    {
        return BusinessLogic::bots()
            ->setBotUser($request->botUser ?? null)
            ->destroyByManager($botId);

    }

    public function restore(Request $request): BotResource
    {

        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->restore();
    }

    /**
     * @throws ValidationException
     */
    public function createKeyboardTemplate(Request $request): BotMenuTemplateResource
    {
        $request->validate([
            "slug" => "required",
            "menu" => "required",
            "type" => "required",
        ]);

        return BusinessLogic::keyboards()
            ->setBot($request->bot ?? null)
            ->create($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function editKeyboardTemplate(Request $request): BotMenuTemplateResource
    {
        $request->validate([
            "id" => "required",
            "slug" => "required",
            "menu" => "required",
            "type" => "required",
        ]);

        $bot = Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::keyboards()
            ->setBot($request->bot ?? null)
            ->update($request->all());
    }

    public function createLocation(Request $request): LocationResource
    {

        return BusinessLogic::companies()
            ->createOrUpdateLocation(
                $request->company_id ?? null,
                $request->all(),
                $request->hasFile('files') ? $request->file('files') : null
            );

    }

    /**
     * @throws ValidationException
     */
    public function createImageMenu(Request $request): ImageMenuResource
    {
        $request->validate([
            'title' => "required|string:255",
            'description' => "required|string:255",
        ]);


        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->createOrUpdateImageMenu(
                $request->all(),
                $request->hasFile('preview') ? $request->file('preview') : null
            );
    }

    public function createBotLazy(Request $request)
    {

        $name = $request->name;
        $token = $request->token ?? null;
        $botDomain = $request->botDomain ?? Str::uuid();

        $bot = Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        if (!is_null($bot))
            $botDomain = Str::uuid()."_bot";


        $botUser = $request->botUser ?? null;

        $greeting = json_decode($request->greeting);
        $contacts = json_decode($request->contacts);
        $selfInfo = json_decode($request->selfInfo);
        $businessInfo = json_decode($request->businessInfo);
        $cashback = json_decode($request->cashback);
        $functions = json_decode($request->functions ?? '[]');
        $neededPages = json_decode($request->pages ?? '[]');

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


        $company = Company::query()->updateOrCreate(
            [
                'slug' => $botDomain
            ],
            [
                'title' => $businessInfo->name,
                'description' => $businessInfo->text,
                'image' => null,
                'address' => $address->value ?? null,
                'phones' => $phones,
                'links' => $links,
                'email' => $email->value ?? null,
                'schedule' => [],
                'manager' => $selfInfo->name,
                'is_active' => true,
                'creator_id' => $botUser->id,
                'owner_id' => null,
                'vat_code' => 1,
                'blocked_message' => "Компания временно недоступна",
                'blocked_at' => null,
            ]);

        /* $greeting_image_avatar = $greeting->need_photo ?
             $this->file($request, $company->slug, "greeting_image_avatar") :
             ($greeting->avatar ?? null);

         $greeting_image_profile = $greeting->need_photo ?
             $this->file($request, $company->slug, "greeting_image_profile") :
             ($greeting->profile ?? null);


         $contacts_image = $contacts->need_photo ?
             $this->file($request, $company->slug, "contacts_image") :
             ($contacts->image ?? null);

         $self_info_image = $selfInfo->need_photo ?
             $this->file($request, $company->slug, "self_info_image") :
             ($selfInfo->image ?? null);

         $business_info_image = $businessInfo->need_photo ?
             $this->file($request, $company->slug, "business_info_image") :
             ($businessInfo->image ?? null);*/

        $photos = [];

        if (!is_null($request->selected_bot_id)) {
            $bot = Bot::query()
                ->find($request->selected_bot_id);

            if (is_null($bot))
                return "error";

            $duplicateBot = BusinessLogic::bots()
                ->setBot($bot ?? null)
                ->duplicate([
                    "company_id" => $company->id ?? null,
                    "bot_token" => $token,
                    "bot_domain" => $botDomain
                ]);

            $pages = BotPage::query()
                ->with(['slug' => function ($query) {
                    $query->select('id', 'command');
                }])
                ->where("bot_id", $duplicateBot->id)
                ->select("bot_menu_slug_id", "bot_id")
                ->get()
                ->toArray();

            $pages = Collection::make($pages)
                ->pluck("slug.command")
                ->all();

            foreach ($neededPages as $neededPage) {
                $neededPage = (object)$neededPage;
                if (in_array($neededPage->title, $pages)) {
                    $slug = BotMenuSlug::query()
                        ->create([
                            'bot_id' => $duplicateBot->id,
                            'command' => $neededPage->title ?? $neededPage->comment ?? 'Нет контента',
                            'comment' => "Автоматические созданная страница $neededPage->title $neededPage->comment",
                            'slug' => Str::uuid(),
                        ]);

                    BotPage::query()->create([
                        'bot_menu_slug_id' => $slug->id,
                        'content' => $neededPage->comment ?? $neededPage->title ?? 'Нет контента',
                        'bot_id' => $duplicateBot->id,
                    ]);
                }
            }

            if (!is_null($token))
                BotManager::bot()->setWebhooks();

            return $duplicateBot;
        }

        $services = [
            "referral-bonus" => [],
            "agent-cabinet" => [],
            "cashback" => [],
            "individual-button" => [],
            "friends-system" => [],
            "amo-crm" => [],
            "coffee-card" => ["global_bonus_product"],
            "insta-quest" => ["global_instagram_quest"],
            "wheel-of-fortune" => ["global_wheel_of_fortune_custom"],
            "payments" => [],
            "courses" => [],
            "atmosphere" => [],
            "booking" => [],
            "custom-shop" => ["global_shop_main"],
            "cost-of-services" => ["global_single_payment_main"],
            "franchise" => [],
            "investors" => [],
            "our-clients" => [],
            "promotions" => [],
            "location" => ["global_geo_main", "global_venue_main"],
            "online-consultation" => [],
            "ask-a-question" => [],
            "reviews" => [],
            "sales-funnel" => [],
            "lead-magnet" => [],
            "telegram-channel-integration" => [],
            "event-form" => [],

        ];


        $botType = BotType::query()
            ->where("slug", "business_card")
            ->first();


        $bot = Bot::query()->create([
            'company_id' => $company->id,
            'welcome_message' => $greeting->text,
            'bot_domain' => $botDomain,
            'bot_token' => $token ?? "test_replacement_token",
            'bot_token_dev' => $token ?? "test_replacement_token",
            'order_channel' => -1,
            'main_channel' => -1,
            'balance' => 30,
            'tax_per_day' => 10,
            'image' => $greeting_image_avatar ?? null,
            'description' => $businessInfo->text,
            'info_link' => null,
            'social_links' => $links,
            'is_active' => true,
            'maintenance_message' => "Техническое обслуживание",
            'bot_type_id' => $botType->id,
            'level_1' => $cashback->level_1 ?? 7,
            'level_2' => $cashback->level_2 ?? 3,
            'level_3' => $cashback->level_3 ?? 1,
            'is_template' => false,
            'template_description' => "Не является шаблоном",
        ]);

        foreach ($services as $slugs) {
            if (empty($slugs))
                continue;

            foreach ($slugs as $slug) {
                $globalSlug = BotMenuSlug::query()
                    ->where("is_global", true)
                    ->whereNull("bot_id")
                    ->where("slug", $slug)
                    ->first();

                if (is_null($globalSlug))
                    continue;

                $localSlug = BotMenuSlug::query()
                    ->where("is_global", true)
                    ->where("bot_id", $bot->id)
                    ->where("slug", $slug)
                    ->first();

                if (!is_null($localSlug))
                    continue;

                $newSlug = $globalSlug->replicate();
                $newSlug->bot_id = $bot->id;
                $newSlug->save();

            }
        }

        $pages = Collection::make($neededPages)
            ->unique("title")
            ->all();

        foreach ($pages as $page) {
            $slug = BotMenuSlug::query()
                ->create([
                    'bot_id' => $bot->id,
                    'command' => $page->title ?? $page->comment ?? 'Нет контента',
                    'comment' => "Автоматические созданная страница $page->title ",
                    'slug' => Str::uuid(),
                ]);

            if ($page->title == "/start") {
                $menu = BotMenuTemplate::query()->create([
                    'bot_id' => $bot->id,
                    'type' => 'inline',
                    'slug' => "first_generic_menu_1",
                    'menu' => [
                        [
                            ["text" => "Меню диагностики системы", "callback_data" => "/diagnostic"],
                        ],
                    ]
                ]);

                BotPage::query()->create([
                    'bot_menu_slug_id' => $slug->id,
                    'content' => "Добрый день, {{userName}}! Вы создали своего нового бота! Это первая диагностическая страница, которая показывает нам работу всех установленных скриптов в систему! Далее вы сможете изменить этот внешний вид.",
                    'bot_id' => $bot->id,
                    'inline_keyboard_id' => $menu->id,
                ]);
            } else
                BotPage::query()->create([
                    'bot_menu_slug_id' => $slug->id,
                    'content' => $page->comment ?? $page->title ?? 'Нет контента',
                    'bot_id' => $bot->id,
                ]);
        }

        if (!is_null($token))
            BotManager::bot()->setWebhooks();


        return new BotResource($bot);
    }

    /**
     * @throws ValidationException
     */
    public function createBot(Request $request): BotResource
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
            "company_id" => "required",
        ]);

        return BusinessLogic::bots()
            ->create(
                $request->all(),
                $request->hasFile('images') ? $request->file('images') : null
            );
    }

    /**
     * @throws ValidationException
     */
    public function updateBot(Request $request): BotResource
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

        ]);


        return BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->update(
                $request->all(),
                $request->hasFile('images') ? $request->file('images') : null
            );
    }

    /**
     * @throws ValidationException
     */
    public function updateBotByManager(Request $request): BotResource
    {
        $request->validate([
            "id" => "required",
            "bot_domain" => "required",
            "bot_token" => "required",

            "balance" => "required",
            "tax_per_day" => "required",
            "description" => "required",

            "social_links" => "required",
            "maintenance_message" => "required",
            "welcome_message" => "required",
            "level_1" => "required",

        ]);


        return BusinessLogic::bots()
            ->setBotUser($request->botUser ?? null)
            ->updateByManager(
                $request->all(),
                $request->hasFile('images') ? $request->file('images') : null
            );
    }

    public function removeKeyboardTemplate(Request $request, $templateId): BotMenuTemplateResource
    {
        return BusinessLogic::keyboards()
            ->setBot($request->bot ?? null)
            ->destroy($templateId);
    }


}
