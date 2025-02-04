<?php

namespace App\Http\Controllers\Admin;

use App\Classes\SystemUtilitiesTrait;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\BotType;
use App\Models\BotUser;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;

class BotController extends Controller
{

    public function getActualTariffs(Request $request)
    {
        // Загружаем тарифы из JSON-файла
        $tariffs = json_decode(File::get(base_path() . '/tariffs.json'), true)['tariffs'];

        // Сортируем тарифы по возрастанию цены
        usort($tariffs, fn($a, $b) => $a['price'] <=> $b['price']);

        return \response()
            ->json($tariffs);
    }

    public function tinkoffInvoiceProductsServiceCallback(Request $request, $domain)
    {

        $bot = Bot::query()
            ->where("bot_domain", $domain)
            ->first();

        if (is_null($bot))
            return response()->json(['message' => 'Error processing payment'], 400);

        BusinessLogic::payment()
            ->setBot($bot)
            ->sbpNotificationProductsPayment($request->all());

        return ['message' => 'OK'];
    }


    public function tinkoffInvoiceServiceCallback(Request $request)
    {
        // Получаем данные из запроса
        $data = $request->all();

        // Проверяем, что платеж успешно завершен
        if (isset($data['Success']) && $data['Success'] == true && isset($data['Status']) && $data['Status'] === 'CONFIRMED') {
            $orderId = $data['OrderId'];  // ID заказа в вашей системе
            $paymentId = $data['PaymentId'];  // ID платежа в Тинькофф
            $amount = $data['Amount'] / 100;  // Конвертируем сумму из копеек в рубли
            $customerKey = $data['CustomerKey'] ?? null;  // Ключ клиента, если передавался

            $bot = Bot::query()
                ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
                ->first();

            $botUser = BotUser::query()
                ->with(["manager"])
                ->where("id", $customerKey)
                ->where("bot_id", $bot->id)
                ->first();

            $manager = $botUser->manager;
            $manager->balabce += $amount;


            // Загружаем тарифы из JSON-файла
            $tariffs = json_decode(File::get(base_path() . '/tariffs.json'), true)['tariffs'];

            // Сортируем тарифы по возрастанию цены
            usort($tariffs, fn($a, $b) => $a['price'] <=> $b['price']);

            // Поиск тарифа
            $selectedTariff = $tariffs[0]; // По умолчанию самый дешевый

            foreach ($tariffs as $tariff) {
                if ($amount >= $tariff['price']) {
                    $selectedTariff = $tariff;
                } else {
                    break;
                }
            }

            $manager->max_company_slot_count += $selectedTariff['slots'] ?? 0;
            $manager->max_bot_slot_count += $selectedTariff['slots'] ?? 0;
            $manager->permanent_personal_discount = max($selectedTariff['discount'] ?? 0, $manager->permanent_personal_discount);
            $manager->save();

            // Здесь можно обновить статус заказа в БД
            // Например, если у вас есть модель Order:
            // $order = Order::where('order_id', $orderId)->first();
            // if ($order) {
            //     $order->update(['status' => 'paid']);
            // }

            // Отвечаем Тинькофф, что callback обработан успешно
            return response()->json(['message' => 'OK'], 200);
        }

        // Если что-то пошло не так, логируем и возвращаем ошибку
        Log::error('Tinkoff Callback Error:', $data);
        return response()->json(['message' => 'Error processing payment'], 400);
    }

    /**
     * @throws ValidationException
     */
    public function sendInvoice(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "amount" => "required|integer",
        ]);

        $bot = Bot::query()
            ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
            ->first();

        $botUser = BotUser::query()
            // ->where("user_id", Auth::user()->id)
            ->where("bot_id", $bot->id)
            ->first();

        $result = BusinessLogic::payment()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->invoiceServiceLink($request->all());

        return response()->json($result);
    }

    public function trafficStatistic(Request $request)
    {

        $request->validate([
            "date" => "required"
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        $botUser = $request->botUser ?? null;


        $traffics = BusinessLogic::stat()
            ->setBot($bot ?? null)
            ->setBotUser($botUser)
            ->traffic(
                $request->date[0] ?? null,
                $request->date[1] ?? null,
                $request->need_all ?? false,
                $sort["direction"] ?? 'asc',
                $sort['key'] ?? 'created_at');

        return response()->json([
            "traffics" => $traffics
        ]);
    }

    public function statistic(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "date" => "required"
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        $botUser = $request->botUser ?? null;

        $sort = $request->sort;


        $statistics = BusinessLogic::stat()
            ->setBot($bot ?? null)
            ->setBotUser($botUser)
            ->base($request->date[0] ?? null,
                $request->date[1] ?? null,
                $request->need_all ?? false,
                $sort["direction"] ?? 'asc',
                $sort['key'] ?? 'price');

        return response()->json([
            'statistic' => $statistics
        ]);
    }

    public function loadCurrentServerList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(BusinessLogic::bots()->getCurrentServers());
    }


    public function getMe(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "bot_token" => "required",
        ]);

        $result = BusinessLogic::bots()
            ->getMe($request->bot_token ?? null);

        return response()->json($result);
    }


    /**
     * @throws ValidationException
     */
    public function sendFeedback(Request $request): Response
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);


        $bot = Bot::query()
            ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendManagerFeedback($request->all());

        return response()->noContent();

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
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendToChannel($request->all(),
                $request->hasFile('photos') ? $request->file('photos') : null
            );
        return response()->noContent();

    }

    /**
     * @throws ValidationException
     */
    public function sendToQueue(Request $request): Response
    {
        $request->validate([
            "message" => "required",
            "bot_id" => "required",
            "cron_time" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id)
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendToRedisQueue($request->all());

        return response()->noContent();

    }


    /**
     * @throws ValidationException
     */
    public function createBotTopics(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "topics.*.key" => "required",
            "topics.*.title" => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        return BusinessLogic::bots()
            ->setBot($bot ?? null)
            ->createBotTopics((array)$request->topics);
    }

    public function loadChatInfo(Request $request)
    {

        $request->validate([
            "chat_id" => "required",
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        return BusinessLogic::bots()
            ->setBot($bot ?? null)
            ->getChat($request->chat_id ?? null);
    }

    public function loadBotFields(Request $request, $botId): \App\Http\Resources\BotCustomFieldSettingCollection
    {
        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $botId)
            ->first();

        return BusinessLogic::bots()
            ->setBot($bot ?? null)
            ->botFieldList();
    }

    /**
     * @throws ValidationException
     */
    public function storeBotFields(Request $request): \App\Http\Resources\BotCustomFieldSettingCollection
    {
        $request->validate([
            "fields.*.key" => "required",
            "fields.*.label" => "required",
            "fields.*.type" => "required",
            "fields.*.description" => "required",
            "fields.*.validate_pattern" => "",
            "fields.*.is_active" => "",
            "fields.*.required" => "",
            "bot_id" => "required",
        ]);


        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        return BusinessLogic::bots()
            ->setBot($bot ?? null)
            ->storeBotFields($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function updateShopLink(Request $request): BotResource
    {
        $request->validate([
            "vk_shop_link" => "required",
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        return BusinessLogic::bots()
            ->setBot($bot ?? null)
            ->updateShopLink($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function sendCallback(Request $request): Response
    {
        $request->validate([
            "bot_domain" => "required",
            "slug_id" => "required",
            "telegram_chat_id" => "required",
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        $bot = \App\Models\Bot::query()
            ->with(["company"])
            ->where("bot_domain", $request->bot_domain)
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendCallback($request->all());

        return response()->noContent();
    }


    /**
     * @throws ValidationException
     */
    public function requestTelegramChannel(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "token" => "required",
            "channel" => "required",
        ]);

        $bot = Bot::query()->where("bot_token", $request->token)->first();

        return response()
            ->json(
                BusinessLogic::bots()
                    ->setBot($bot ?? null)
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

        $logic = BusinessLogic::slugs();

        if ($request->botUser->is_manager && !$request->botUser->is_admin)
            $logic = $logic->setBotUser($request->botUser);


        return $logic
            ->globals();
    }

    /**
     * @throws ValidationException
     */
    public function changeUserStatus(Request $request): Response
    {
        $request->validate([
            "bot_user_id" => "required",
            "status" => "required",
            "type" => "required",
        ]);

        BusinessLogic::bots()
            ->changeUserStatus($request->all());

        return response()->noContent();
    }

    public function loadBotUsers(Request $request): \App\Http\Resources\BotUserCollection
    {
        $request->validate([
            "botId" => "required"
        ]);

        $bot = Bot::query()->find($request->botId);

        return BusinessLogic::botUsers()
            ->setBot($bot)
            ->list(
                search: $request->search ?? null,
                size: $request->size ?? null
            );
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

    public function loadImageMenu(Request $request, $botId): \App\Http\Resources\ImageMenuCollection
    {
        $bot = Bot::query()
            ->with(["company", "imageMenus"])
            ->find($botId);

        return BusinessLogic::bots()
            ->setBot($bot)
            ->imageMenuList();
    }


    public function loadKeyboards(Request $request, $botId): \App\Http\Resources\BotMenuTemplateCollection
    {
        $bot = Bot::query()->find($botId);

        return BusinessLogic::keyboards()
            ->setBot($bot)
            ->list();
    }

    public function loadPages(Request $request, $botId): \App\Http\Resources\BotPageCollection
    {
        $bot = Bot::query()->find($botId);

        return BusinessLogic::pages()
            ->setBot($bot)
            ->list(
                null,
                $request->get("size") ?? config('app.results_per_page'),
            );
    }

    public function loadSlugs(Request $request, $botId): \App\Http\Resources\BotMenuSlugCollection
    {
        $bot = Bot::query()->find($botId);

        return BusinessLogic::slugs()
            ->setBot($bot)
            ->list(

                null,
                $request->get("size") ?? config('app.results_per_page'),
                ($request->isGlobal ?? false) == "true"
            );
    }


    public function listByIds(Request $request): \App\Http\Resources\BotCollection
    {
        $request->validate([
            "ids" => "required|array"
        ]);

        $logic = BusinessLogic::bots();

        if ($request->botUser->is_manager && !$request->botUser->is_admin)
            $logic = $logic->setBotUser($request->botUser);

        return $logic
            ->listByIds(
                $request->ids ?? null,
            );
    }

    public function index(Request $request): \App\Http\Resources\BotCollection
    {

        $logic = BusinessLogic::bots();

        if ($request->botUser->is_manager && !$request->botUser->is_admin)
            $logic = $logic->setBotUser($request->botUser);

        return $logic
            ->list(
                $request->companyId ?? null,
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),
                $request->order ?? null,
                $request->direction ?? null,
                $request->filters ?? []
            );
    }


    /**
     * @throws ValidationException
     */
    public function duplicate(Request $request): BotResource
    {
        $request->validate([
            "bot_id" => "required"
        ]);

        $botUser = $request->botUser ?? null;

        $bot = Bot::query()
            ->find($request->bot_id ?? null);

        return BusinessLogic::bots()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->duplicate(["company_id" => $request->company_id ?? null]);
    }


    public function forceDelete(Request $request, $botId): Response
    {
        $bot = Bot::query()
            ->withTrashed()
            ->find($botId);

        BusinessLogic::bots()
            ->setBot($bot)
            ->forceDelete();

        return response()->noContent();
    }

    public function destroy(Request $request, $botId): BotResource
    {
        $bot = Bot::query()->find($botId);

        return BusinessLogic::bots()
            ->setBot($bot)
            ->destroy();

    }

    public function restore(Request $request, $botId): BotResource
    {
        $bot = Bot::query()
            ->withTrashed()
            ->find($botId);

        return BusinessLogic::bots()
            ->setBot($bot)
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
            "bot_id" => "required",
        ]);

        $bot = Bot::query()->find($request->bot_id ?? null);
        return BusinessLogic::keyboards()
            ->setBot($bot)
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
            "bot_id" => "required",
        ]);

        $bot = Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::keyboards()
            ->setBot($bot)
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
            'bot_id' => "required",
        ]);

        $bot = Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::bots()
            ->setBot($bot)
            ->createOrUpdateImageMenu(
                $request->all(),
                $request->hasFile('preview') ? $request->file('preview') : null
            );
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
            //"description" => "required",
            "maintenance_message" => "required",
            //"welcome_message" => "required",
            "level_1" => "required",
            // "company_id" => "required",
        ], [
            //"company_id"=>"Вы не указали идентификатор Клиента (Компании)",
            "bot_domain" => "Доменное имя не должно быть пустым и должно быть уникальным!",
        ]);

        $botUser = $request->botUser ?? null;

        return BusinessLogic::bots()
            ->setBotUser($botUser)
            ->create(
                $request->all(),
                $request->hasFile('images') ? $request->file('images') : null
            );
    }

    public function updateWebhook(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id)
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->updateWebHookAndConfig($request->bot_server ?? null);
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


        $botUser = $request->botUser ?? null;

        $bot = Bot::query()
            ->where("id", $request->id)
            ->first();

        //dd($request->id);

        return BusinessLogic::bots()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->update(
                $request->all(),
                $request->hasFile('images') ? $request->file('images') : null
            );
    }

    public function removeKeyboardTemplate(Request $request, $templateId): BotMenuTemplateResource
    {
        return BusinessLogic::keyboards()
            ->destroy($templateId);
    }


}
