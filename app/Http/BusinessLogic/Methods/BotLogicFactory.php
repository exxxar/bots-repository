<?php

namespace App\Http\BusinessLogic\Methods;

use App\Exports\BotCashBackExport;
use App\Exports\BotStatisticExport;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\BotCollection;
use App\Http\Resources\BotCustomFieldSettingCollection;
use App\Http\Resources\BotCustomFieldSettingResource;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotNoteResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotSecurityCollection;
use App\Http\Resources\BotSecurityResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\CashBackCollection;
use App\Http\Resources\CashBackHistoryCollection;
use App\Http\Resources\CashBackResource;
use App\Http\Resources\ImageMenuCollection;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\QueueResource;
use App\Jobs\SendMediaJob;
use App\Jobs\SendMessageJob;
use App\Jobs\SendPhotoJob;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotCustomFieldSetting;
use App\Models\BotDialogCommand;
use App\Models\BotDialogGroup;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotNote;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\BotUser;
use App\Models\BotWarning;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Company;
use App\Models\ImageMenu;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class BotLogicFactory
{
    use LogicUtilities;


    protected $bot;

    protected $botUser;

    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;
    }

    /**
     * @throws HttpException
     */
    public function setBot($bot = null): static
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

    /**
     * @return array|null
     */
    public function getCurrentServers(): ?array
    {
        if (!file_exists(base_path() . "/servers.json"))
            return null;

        $d = file_get_contents(base_path() . "/servers.json");

        $servers = Collection::make(json_decode($d))->toArray();
        foreach ($servers as $key => $value) {
            $count = Bot::query()->where("server", $value->key)->count() ?? 0;
            $servers[$key]->current_count = $count;
        }

        return $servers;
    }

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser = null): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    public function listByIds(array $ids): BotCollection
    {
        $bots = Bot::query()
            ->with(["amo"])
            ->whereIn("id", $ids);

        if (!is_null($this->botUser))
            $bots = $bots->where("creator_id", $this->botUser->id);

        $bots = $bots->get();

        $tmpBots = [];

        foreach ($ids as $id) {
            foreach ($bots as $bot)
                if ($bot->id == $id)
                    $tmpBots[] = $bot;
        }
        return new BotCollection($tmpBots);
    }

    public function list($companyId = null, $search = null, $size = null, $order = null, $direction = null, $filters = []): BotCollection
    {

        $size = $size ?? config('app.results_per_page');

        $bots = Bot::query()
            ->with(["amo"]);


        if (in_array("archive", $filters) || count($filters) == 0)
            $bots = $bots->withTrashed();

        if (in_array("archive", $filters) && count($filters) == 1)
            $bots = $bots->whereNotNull("deleted_at");

        if (!is_null($companyId))
            $bots = $bots->where("company_id", $companyId);

        if (!is_null($this->botUser))
            $bots = $bots->where("creator_id", $this->botUser->id);

        if (!is_null($search))
            $bots = $bots->where(function ($q) use ($search) {
                $q->where("bot_domain", 'like', "%$search%")
                    ->orWhere("title", 'like', "%$search%");
            });

        $bots = $bots
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new BotCollection($bots);
    }

    public function landingCollections($search = null, $size = null): BotSecurityCollection
    {

        $size = $size ?? config('app.results_per_page');

        $bots = Bot::query();

        if (!is_null($search))
            $bots = $bots->where("bot_domain", 'like', "%$search%");

        $bots = $bots
            ->whereNotNull("bot_token")
            ->orderBy("updated_at", 'DESC')
            ->paginate($size);

        return new BotSecurityCollection($bots);
    }

    public function simple($companyId = null, $search = null, $needSelfBots = false, $size = null): BotSecurityCollection
    {
        if (is_null($this->botUser))
            throw new HttpException(400, "Менеджер не указан!");

        $size = $size ?? config('app.results_per_page');

        $bots = Bot::query();

        if ($needSelfBots) {
            /* $bots = $bots->whereHas("company", function ($q) {
                 $q->where("creator_id", $this->botUser->id)
                     ->orderBy("updated_at", 'DESC');
             });*/

            $bots = $bots->where("creator_id", $this->botUser->id);
        }


        if (!is_null($companyId))
            $bots = $bots->where("company_id", $companyId);

        if (!is_null($search))
            $bots = $bots->where("bot_domain", 'like', "%$search%");

        $bots = $bots
            ->orderBy("updated_at", 'DESC')
            ->paginate($size);

        return new BotSecurityCollection($bots);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function duplicate(array $customParams = null): BotResource
    {
        if (is_null($this->botUser))
            throw new HttpException(403, "Условия функции не выполнены");

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $botDomain = $customParams["bot_domain"] ?? null;
        $tmpBot = Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        if (!is_null($tmpBot))
            throw new HttpException(403, "Указанный бот уже существует в системе");

        if (is_null($customParams["company_id"] ?? null)) {
            $company = Company::query()->create([
                'title' => "Новый клиент",
                'slug' => Str::uuid(),
                'description' => "Автоматические создание нового клиента при дублировании бота от " . Carbon::now("+3")->format("Y-m-d H:i:s"),
                'creator_id' => $this->botUser->id ?? null,
            ]);
        } else
            $company = Company::query()->find($customParams["company_id"]);

        if (is_null($company))
            throw new HttpException(403, "Ошибка автоматического создания клиента");

        $newBot = $this->bot->replicate();
        $newBot->deleted_at = null;
        $newBot->bot_domain = $botDomain ?? "duplicate_" . $newBot->bot_domain . "_" . Carbon::now("+3")->format("Y-m-d H:i:s");
        $newBot->bot_token = $customParams["bot_token"] ?? null;
        $newBot->bot_token_dev = $customParams["bot_token_dev"] ?? null;
        $newBot->main_channel = $customParams["main_channel"] ?? null;
        $newBot->order_channel = $customParams["order_channel"] ?? null;
        $newBot->company_id = $company->id;
        $newBot->is_template = false;
        $newBot->template_description = null;
        $newBot->creator_id = $this->botUser->id;
        $newBot->balance = 70;
        $newBot->tax_per_day = 10;
        $newBot->deleted_at = null;
        $newBot->is_active = true;
        $newBot->save();


        $pages = BotPage::query()
            ->with(["slug", "replyKeyboard", "inlineKeyboard"])
            ->where("bot_id", $this->bot->id)
            ->get();

        $replicated = [];

        if (!empty($pages))
            foreach ($pages as $page) {

                $isReplicated = in_array((object)[
                    "type" => "page",
                    "id" => $page->id
                ], $replicated);

                if ($isReplicated)
                    continue;

                $slug = $page->slug ?? null;
                $replyKeyboard = $page->replyKeyboard ?? null;
                $inlineKeyboard = $page->inlineKeyboard ?? null;

                if (is_null($slug))
                    continue;

                $replicated[] = (object)[
                    "type" => "slug",
                    "id" => $slug->id
                ];

                $slug = $slug->replicate();
                $slug->bot_id = $newBot->id;
                $slug->save();


                if (!is_null($replyKeyboard)) {

                    $replicated[] = (object)[
                        "type" => "keyboard",
                        "id" => $replyKeyboard->id
                    ];

                    $replyKeyboard = $replyKeyboard->replicate();
                    $replyKeyboard->bot_id = $newBot->id;
                    $replyKeyboard->save();
                }

                if (!is_null($inlineKeyboard)) {

                    $replicated[] = (object)[
                        "type" => "keyboard",
                        "id" => $inlineKeyboard->id
                    ];

                    $inlineKeyboard = $inlineKeyboard->replicate();
                    $inlineKeyboard->bot_id = $newBot->id;
                    $inlineKeyboard->save();
                }

                $newPage = $page->replicate();
                $newPage->bot_id = $newBot->id;
                $newPage->bot_menu_slug_id = $slug->id ?? null;
                $newPage->reply_keyboard_id = $replyKeyboard->id ?? null;
                $newPage->inline_keyboard_id = $replyKeyboard->id ?? null;
                $newPage->next_page_id = null;
                $newPage->next_bot_dialog_command_id = null;
                $newPage->next_bot_menu_slug_id = null;
                $newPage->save();

            }

        $slugs = BotMenuSlug::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($slugs))
            foreach ($slugs as $slug) {
                $isReplicated = in_array((object)[
                    "type" => "slug",
                    "id" => $slug->id
                ], $replicated);

                if ($isReplicated)
                    continue;

                $replicated[] = (object)[
                    "type" => "slug",
                    "id" => $slug->id
                ];

                $slug = $slug->replicate();
                $slug->bot_id = $newBot->id;
                $slug->save();

            }


        $keyboards = BotMenuTemplate::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($keyboards))
            foreach ($keyboards as $keyboard) {
                $isReplicated = in_array((object)[
                    "type" => "keyboard",
                    "id" => $keyboard->id
                ], $replicated);

                if ($isReplicated)
                    continue;

                $replicated[] = (object)[
                    "type" => "keyboard",
                    "id" => $keyboard->id
                ];

                $keyboard = $keyboard->replicate();
                $keyboard->bot_id = $newBot->id;
                $keyboard->slug = Str::uuid();
                $keyboard->save();

            }

        $dialogs = BotDialogCommand::query()
            ->where("bot_id", $this->bot->id)
            ->get();


        if (!empty($dialogs))
            foreach ($dialogs as $dialog) {
                $isReplicated = in_array((object)[
                    "type" => "dialog",
                    "id" => $dialog->id
                ], $replicated);

                if ($isReplicated)
                    continue;

                $replicated[] = (object)[
                    "type" => "dialog",
                    "id" => $dialog->id
                ];


                $baseGroup = BotDialogGroup::query()
                    ->where("slug", "default_bot_group_slug")
                    ->where("bot_id", $newBot->id)
                    ->first();

                if (is_null($baseGroup))
                    $baseGroup = BotDialogGroup::query()
                        ->create([
                            'slug' => "default_bot_group_slug",
                            'title' => "Группа по умолчанию",
                            'bot_id' => $newBot->id,
                        ]);


                $newDialog = $dialog->replicate();
                $newDialog->bot_id = $newBot->id;
                $newDialog->slug = Str::uuid();
                $newDialog->inline_keyboard_id = null;
                $newDialog->next_bot_dialog_command_id = null;
                $newDialog->bot_dialog_group_id = $baseGroup->id;
                $newDialog->save();

            }


        $newBot = $newBot->fresh();

        Log::info("creator_id2=>" . print_r($newBot->creator_id, true) . " - " . print_r($this->botUser->id, true));

        return new BotResource($newBot);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function createBotTopics(array $data): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не выполнено условие функции");

        $validator = Validator::make($data, [
            "*.key" => "required",
            "*.title" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $topics = $data;

        $botToken = $this->bot->bot_token;
        $chatId = $this->bot->order_channel;

        $website = "https://api.telegram.org/bot" . $botToken;

        $index = 0;
        foreach ($topics as $item) {
            $item = (object)$item;
            $result = Http::post("$website/createForumTopic", [
                'chat_id' => $chatId,
                'name' => $item->title ?? $item->key,
            ]);

            if ($result->object()->ok)
                $topics[$index]["value"] = $result->object()->result->message_thread_id ?? 0;

            if (!$result->object()->ok) {

                $adminBot = Bot::query()
                    ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
                    ->first();

                if (!is_null($adminBot)) {
                    $adminBotUser = BotUser::query()
                        ->where("bot_id", $adminBot->id)
                        ->where("user_id", Auth::user()->id)
                        ->first();

                    if (!is_null($adminBotUser)) {
                        BotMethods::bot()
                            ->whereBot($adminBot)
                            ->sendMessage(
                                $adminBotUser->telegram_chat_id,
                                "Ошибка создания топиков в группе: " . ($result->object()->description ?? 'Ошибка')
                            );
                    }
                }

                throw new HttpException(400, $result->object()->description ?? 'Ошибка');
            }


            $index++;
        }

        $this->bot->message_threads = $topics;
        $this->bot->save();

        return $topics;
    }


    /**
     * @throws HttpException
     */
    public function getMe(string $botToken = null): object|array|null
    {
        if (is_null($botToken))
            throw new HttpException(400, "Не выполнено условие функции");

        $website = "https://api.telegram.org/bot" . $botToken;


        $result = Http::post("$website/getMe");

        return $result->object()->ok ? $result->object()->result : null;

    }

    /**
     * @throws HttpException
     */
    public function getChat($chatId = null): object|array
    {
        if (is_null($this->bot) || is_null($chatId))
            throw new HttpException(400, "Не выполнено условие функции");

        $botToken = $this->bot->bot_token;
        $botDomain = $this->bot->bot_domain ?? 'не найден';
        $website = "https://api.telegram.org/bot" . $botToken;

        $result = Http::post("$website/getChat", [
            'chat_id' => $chatId,
        ]);

        $adminBot = Bot::query()
            ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
            ->first();

        if (!is_null($adminBot)) {
            $adminBotUser = BotUser::query()
                ->where("bot_id", $adminBot->id)
                ->where("user_id", Auth::user()->id)
                ->first();

            if (!is_null($adminBotUser)) {

                $data = $result->object();

                if ($data->ok) {
                    $link = $data->result->invite_link ?? $data->result->username ?? null;
                    BotMethods::bot()
                        ->whereBot($adminBot)
                        ->sendMessage(
                            $adminBotUser->telegram_chat_id,
                            "Ссылка на канал: $chatId (для бота $botDomain) => $link"
                        );
                }

                if (!$data->ok) {
                    BotMethods::bot()
                        ->whereBot($this->bot)
                        ->sendMessage(
                            $adminBotUser->telegram_chat_id,
                            "Ошибка получения ссылки на канал: " . ($data->description ?? 'Ошибка')
                        );

                    throw new HttpException(400, $data->description ?? 'Ошибка');
                }

            }
        }


        return $result->object();
    }

    /**
     * @throws HttpException
     */
    public function prepareBaseBotConfig(): void
    {

        if (is_null($this->bot))
            throw new HttpException(400, "Не выполнено условие функции");

        $botToken = $this->bot->bot_token;
        $website = "https://api.telegram.org/bot" . $botToken;

        if (!is_null($this->bot->title ?? null))
            Http::post("$website/setMyName", [
                'name' => $this->bot->title,
            ]);

        if (!is_null($this->bot->short_description ?? null))
            Http::post("$website/setMyShortDescription", [
                'short_description' => $this->bot->short_description,
            ]);

        if (!is_null($this->bot->long_description ?? null))
            Http::post("$website/setMyDescription", [
                'description' => $this->bot->long_description,
            ]);


        Http::post("$website/setMyCommands", [
            'commands' => $this->bot->commands ?? [
                    [
                        "command" => "/start", "description" => "начни с этой команды"
                    ],
                    [
                        "command" => "/admins", "description" => "доступные администраторы в системе"
                    ],
                    [
                        "command" => "/help", "description" => "как использовать систему"
                    ],
                    [
                        "command" => "/about", "description" => "о CashMan"
                    ]
                ],
        ]);


        if (!is_null($this->bot->menu["url"] ?? null))
            Http::post("$website/setChatMenuButton", [
                'menu_button' => [
                    "type" => "web_app",
                    "text" => $this->bot->menu["text"] ?? 'Меню',
                    "web_app" => [
                        "url" => $this->bot->menu["url"] ?? null,
                    ]
                ],
            ]);


    }

    /**
     * @throws HttpException
     */
    public function forceDelete(): void
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        Schema::disableForeignKeyConstraints();

        $pages = BotPage::query()
            ->with(["slug", "replyKeyboard", "inlineKeyboard"])
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($pages))
            foreach ($pages as $page)
                $page->delete();

        $slugs = BotMenuSlug::query()
            ->where("bot_id", $this->bot->id)
            ->get();


        if (!empty($slugs))
            foreach ($slugs as $slug)
                $slug->delete();

        $keyboards = BotMenuTemplate::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($keyboards))
            foreach ($keyboards as $keyboard)
                $keyboard->delete();

        $dialogs = BotDialogCommand::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($dialogs))
            foreach ($dialogs as $dialog)
                $dialog->delete();

        $groups = BotDialogGroup::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($groups))
            foreach ($groups as $group)
                $group->delete();

        $menus = ImageMenu::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($menus))
            foreach ($menus as $menu)
                $menu->delete();

        $cashbacks = CashBack::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($cashbacks))
            foreach ($cashbacks as $cashback)
                $cashback->delete();

        $products = Product::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($products))
            foreach ($products as $product)
                $product->delete();

        $amos = AmoCrm::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($amos))
            foreach ($amos as $amo)
                $amo->delete();

        $this->bot->forceDelete();
        Schema::enableForeignKeyConstraints();


    }

    /**
     * @throws HttpException
     */
    public function destroy(): BotResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $tmpBot = $this->bot;
        $this->bot->deleted_at = Carbon::now();
        $this->bot->save();

        return new BotResource($tmpBot);
    }

    /**
     * @throws HttpException
     */
    public function destroyByManager($botId): BotResource
    {
        if (is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $bot = Bot::query()
            ->where("id", $botId)
            ->whereHas("company", function ($q) {
                $q->where("creator_id", $this->botUser->id);
            })
            ->first();

        if (is_null($bot))
            throw new HttpException(404, "Бот не принадлежит данному менеджеру");

        $tmpBot = $bot;
        $bot->deleted_at = Carbon::now();
        $bot->save();

        return new BotResource($tmpBot);
    }

    /**
     * @throws HttpException
     */
    public function restore(): BotResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $this->bot->deleted_at = null;
        $this->bot->save();

        return new BotResource($this->bot);
    }

    /**
     * @throws HttpException
     */
    public function botFieldList(): BotCustomFieldSettingCollection
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $fields = BotCustomFieldSetting::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        return new BotCustomFieldSettingCollection($fields);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function storeMessageSettings(array $data): BotSecurityResource
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");


        $settings = $data["settings"] ?? [];

        $config = $this->bot->config ?? [];

        foreach ($settings as $key => $value) {
            $config[$key] = $value ?? null;
        }

        $this->bot->config = $config;
        $this->bot->save();

        return new BotSecurityResource($this->bot);

    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function storeBotFields(array $data): BotCustomFieldSettingCollection
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "fields.*.key" => "required",
            "fields.*.label" => "required",
            "fields.*.type" => "required",
            "fields.*.description" => "required",
            "fields.*.required" => "",
            "fields.*.validate_pattern" => "",
            "fields.*.is_active" => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $fields = json_decode($data["fields"]);

        foreach ($fields as $field) {
            $field = (object)$field;

            BotCustomFieldSetting::query()
                ->updateOrCreate([
                    'bot_id' => $this->bot->id,
                    'key' => $field->key,
                ], [
                    'type' => $field->type,
                    'label' => $field->label,
                    'description' => $field->description,
                    'required' => ($field->required ?? false) == "on" ? 1 : 0,
                    'validate_pattern' => $field->validate_pattern,
                    'is_active' => ($field->is_active ?? false) == "on" ? 1 : 0,
                ]);
        }

        $fields = BotCustomFieldSetting::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        return new BotCustomFieldSettingCollection($fields);

    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function updateShopLink(array $data): BotResource
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "vk_shop_link" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $this->bot->vk_shop_link = $data["vk_shop_link"] ?? null;
        $this->bot->save();

        return new BotResource($this->bot);

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function getManagerBot(array $data): BotResource
    {
        if (is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "botId" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $bot = Bot::query()
            ->where("id", $data["botId"])
            ->whereHas("company", function ($q) {
                $q->where("creator_id", $this->botUser->id);
            })
            ->first();

        if (is_null($bot))
            throw new HttpException(404, "Бот не принадлежит данному менеджеру");

        return new BotResource($bot);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function sendLandingRequest(array $data): void
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $callbackChannel = $this->bot->order_channel ?? $this->bot->main_channel ?? env("BASE_ADMIN_CHANNEL");

        $typeText = "#заявкаслендинга";

        $adminMessage = "$typeText\n -имя: %s \n -телефон: %s\n -почта: %s\nСообщение: %s\n";

        $thread = $this->bot->topics["orders"] ?? null;

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage($callbackChannel,
                sprintf($adminMessage,
                    $data["name"] ?? '-',
                    $data["phone"] ?? '-',
                    $data["email"] ?? '-',
                    $data["message"] ?? '-'
                ), $thread);

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function sendFeedback(array $data, $uploadedPhotos = null): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $feedbackChannel = $this->bot->main_channel ?? null;
        $adminChannel = $this->bot->order_channel ?? null;

        $feedbackMessage = "#отзыв_клиента\n<em>%s</em>\n";
        $adminMessage = "#отзыв_клиента\n -имя: %s \n  -тг id: %s\n -телефон: %s\nСообщение: %s\n";

        $thread = $this->bot->topics["callback"] ?? null;

        if (!is_null($uploadedPhotos)) {

            if (count($uploadedPhotos) > 1) {
                $media = [];
                foreach ($uploadedPhotos as $key => $photo) {
                    $ext = $photo->getClientOriginalExtension();

                    $imageName = Str::uuid() . "." . $ext;

                    $photo->storeAs("/public/companies/" . $this->bot->company->slug . "/$imageName");

                    $media[] = [
                        "media" => env("APP_URL") . "/images/" . $this->bot->company->slug . "/" . $imageName,
                        "type" => "photo",
                        "caption" => "$imageName"
                    ];

                }


                BotMethods::bot()
                    ->whereBot($this->bot)
                    ->sendMediaGroup($feedbackChannel, json_encode($media))
                    ->sendMessage($feedbackChannel, sprintf($feedbackMessage,
                        $data["message"] ?? '-'
                    ));
            } else {
                $ext = $uploadedPhotos[0]->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $uploadedPhotos[0]->storeAs("$imageName");

                BotMethods::bot()
                    ->whereBot($this->bot)
                    ->sendPhoto(
                        $feedbackChannel,
                        sprintf($feedbackMessage,
                            $data["message"] ?? '-'
                        ),
                        InputFile::create(storage_path() . "/app/$imageName")
                    );
            }

        } else {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $feedbackChannel,
                    sprintf($feedbackMessage,
                        $data["message"] ?? '-'
                    )
                );
        }


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage($adminChannel,
                sprintf($adminMessage,
                    $data["name"] ?? '-',
                    $this->botUser->telegram_chat_id ?? '-',
                    $data["phone"] ?? '-',
                    $data["message"] ?? '-'
                ), $thread);

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function sendCallback(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $type = $data["type"] ?? null;

        $callbackChannel = $this->bot->order_channel ?? env("BASE_ADMIN_CHANNEL");

        $typeText = match ($type) {
            'booking' => "#бронированиестолика",
            default => "#обратнаясвязь",
        };

        $adminMessage = "$typeText\nБот: %s\nСкрипт: #%s (название скрипта: %s) \nПользователь: \n -tg id: %s \n -имя: %s \n -телефон: %s)\nСообщение: %s\n";

        $thread = $this->bot->topics["callback"] ?? null;

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage($callbackChannel,
                sprintf($adminMessage,
                    $this->bot->bot_domain,
                    $this->slug->id,
                    $this->slug->slug,
                    $this->botUser->telegram_chat_id ?? '-',
                    $data["name"] ?? '-',
                    $data["phone"] ?? '-',
                    $data["message"] ?? '-'
                ), $thread);

    }


    /**
     * @throws ValidationException
     */
    public function notes(): mixed
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $botNotes = BotNote::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->orderBy("created_at", "desc")
            ->get();

        if (count($botNotes) == 0)
            return null;

        return new BotNoteResource($botNotes);

    }

    /**
     * @throws ValidationException
     */
    public function requestTelegramChannel(array $data): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "channel" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $token = $this->bot->bot_token;
        $channel = $data["channel"];

        try {
            $res = Http::get("https://api.telegram.org/bot$token/sendMessage?chat_id=$channel&text=channelId");

            return $res->json();
        } catch (\Exception $exception) {
            return null;
        }

    }


    public function descriptions(): array
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

        return $tmp;
    }


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function changeUserStatus(array $data): void
    {
        $validator = Validator::make($data, [
            "bot_user_id" => "required", //todo: сделать bot_user_id
            "status" => "required",
            "type" => "required"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $botUser = BotUser::query()
            ->where("id", $data["bot_user_id"])
            ->first();

        if (is_null($botUser))
            throw new HttpException(404, "Пользователь бота не найден");

        $tmp[$data["type"]] = $data["status"] == 1;
        $botUser->update($tmp);

        $status = match ($data["type"] ?? '') {
            "is_admin" => $data["status"] == 1 ? "❌ Пользователь => ✅️Администратор" : "❌ Администратор => ✅️Пользователь",
            "is_vip" => $data["status"] == 1 ? "❌ Обычный пользователь => ✅️VIP" : "❌ VIP => ✅️Обычный пользователь",
            "is_manager" => $data["status"] == 1 ? "❌ Обычный пользователь => ✅️Менеджер" : "❌ Менеджер => ✅️Обычный пользователь",
            "is_deliveryman" => $data["status"] == 1 ? "❌ Обычный пользователь=> ✅️Доставщик" : "❌ Доставщик => ✅️Обычный пользователь",
            "is_work" => $data["status"] == 1 ? "✅️За работой" : "❌ Не работает",
            default => "неизвестный статус",
        };

        BotMethods::bot()
            ->whereId($botUser->bot_id)
            ->sendMessage($botUser->telegram_chat_id,
                "Вам изменили статус учетной записи на \"$status\""
            );

    }


    public function templateList(): array
    {
        $bots = Bot::query()
            ->where("is_template", true)
            ->select("title", "description", "bot_domain", "id", "template_description")
            ->get();


        return $bots->toArray();
    }


    /**
     * @throws HttpException
     */
    public function imageMenuList(): ImageMenuCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $menus = ImageMenu::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        return new ImageMenuCollection($menus);
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
    public function create(array $data, array $uploadedPhotos = null): BotResource
    {
        if (is_null($this->botUser))
            throw new HttpException(403, "Условия функции не выполнены");


        $validator = Validator::make($data, [
            "bot_domain" => "required|unique:bots,bot_domain",
            "bot_token" => "required",

            "balance" => "required",
            "tax_per_day" => "required",
            //"description" => "required",

            "maintenance_message" => "required",
            // "welcome_message" => "required",
            "level_1" => "required",
            // "selected_bot_template_id" => "required",
            //"slugs" => "required",
            //"pages" => "required",
            //"keyboards" => "required",
            //"company_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $findBot = Bot::query()
            ->withTrashed()
            ->where("bot_domain", $data["bot_domain"])->first();

        if (!is_null($findBot)) {
            throw new HttpException(409,
                !is_null($findBot->deleted_at) ?
                    "Бот с именем " . $data["bot_domain"] . " уже существует в удаленных ботах!" :
                    "Бот с именем " . $data["bot_domain"] . " уже существует!"
            );

        }

        if (is_null($customParams["company_id"] ?? null)) {
            $company = Company::query()->create([
                'title' => "Новый клиент",
                'slug' => Str::uuid(),
                'description' => "Автоматические создание нового клиента при дублировании бота от " . Carbon::now("+3")->format("Y-m-d H:i:s"),
                'creator_id' => $this->botUser->user_id ?? null,
            ]);
        } else
            $company = Company::query()->find($customParams["company_id"]);

        if (is_null($company))
            throw new HttpException(403, "Ошибка автоматического создания клиента");


        $photos = $this->uploadPhotos("/public/companies/$company->slug", $uploadedPhotos);

        $botType = BotType::query()->where("slug", "cashback")->first();

        $tmp = (object)$data;

        $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);
        $tmp->level_2 = $request->level_2 ?? 0;
        $tmp->level_3 = $request->level_3 ?? 0;
        $tmp->server = $request->server ?? null;
        $tmp->message_threads = isset($data["message_threads"]) ? json_decode($data["message_threads"] ?? '[]') : null;
        $tmp->cashback_config = isset($data["cashback_config"]) ? json_decode($data["cashback_config"] ?? '[]') : null;
        $tmp->config = isset($data["config"]) ? json_decode($data["config"] ?? '[]') : null;

        $tmp->menu = isset($data["menu"]) ? json_decode($data["menu"] ?? '[]') : null;
        $tmp->commands = isset($data["commands"]) ? json_decode($data["commands"] ?? '[]') : null;

        $tmp->company_id = $company->id;
        $tmp->bot_type_id = $botType->id;
        $tmp->cashback_fire_percent = $data["cashback_fire_percent"] ?? 0;
        $tmp->cashback_fire_period = $data["cashback_fire_period"] ?? 0;
        $tmp->max_cashback_use_percent = $data["max_cashback_use_percent"] ?? 0;
        $tmp->is_active = true;
        $tmp->auto_cashback_on_payments = $data["auto_cashback_on_payments"] == "true";
        $tmp->is_template = $data["is_template"] == "true";

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $tmp->creator_id = !$tmp->is_template ? $this->botUser->id : null;

        $keyboards = null;
        if (isset($data["keyboards"])) {
            $keyboards = json_decode($data["keyboards"]);
            unset($tmp->keyboards);
        }
        $slugs = null;

        if (isset($data["slugs"])) {
            $slugs = json_decode($data["slugs"]);
            unset($tmp->slugs);
        }

        $pages = null;

        if (isset($data["pages"])) {
            $pages = json_decode($data["pages"]);
            unset($tmp->pages);
        }

        $warnings = null;
        if (isset($data["warnings"])) {
            $warnings = json_decode($data["warnings"]);
            unset($tmp->warnings);
        }

        if (!is_null($tmp->selected_bot_template_id))
            unset($tmp->selected_bot_template_id);


        $bot = Bot::query()->create((array)$tmp);

        if (!is_null($pages))
            if (isset($pages->data))
                foreach ($pages->data as $page) {
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
                    'is_global' => $slug->is_global ?? false,
                    'config' => $slug->config ?? null,
                ]);

        if (!is_null($keyboards))
            foreach ($keyboards as $keyboard)
                BotMenuTemplate::query()->create([
                    'bot_id' => $bot->id,
                    'type' => $keyboard->type,
                    'slug' => $keyboard->slug,
                    'menu' => $keyboard->menu,
                ]);

        if (!is_null($warnings))
            foreach ($warnings as $warn)
                BotWarning::query()->create([
                    'bot_id' => $bot->id,
                    'rule_key' => $warn->rule_key ?? null,
                    'rule_value' => $warn->rule_value ?? null,
                    'is_active' => $warn->is_active ?? false,
                ]);

        $bot = $bot->fresh();


        return new BotResource($bot);
    }


    public function updatMenuIcons(array $data, $files = [])
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Условия функции не выполнены!");

        $data = json_decode($data["items"] ?? '[]');

        $config = $this->bot->config ?? [];

        if (count($files ?? []) > 0) {

            foreach ($files as $key => $value) {
                $file = $value[0];
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/shop-v2-2/' . $this->bot->bot_domain), $filename);

                for ($i = 0; $i < count($data ?? []); $i++) {
                    if ($data[$i]->slug == $key) {
                        $oldPath = public_path('images/shop-v2-2/' . $this->bot->bot_domain . "/" . $data[$i]->image_url);
                        if (file_exists($oldPath))
                            unlink($oldPath);
                        $data[$i]->image_url = $this->bot->bot_domain . "/" . $filename;
                    }

                }


            }

            $config["icons"] = $data;
        }

        $this->bot->config = $config;
        $this->bot->save();

        return new BotResource($this->bot);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function updateParams(array $data): BotResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Условия функции не выполнены!");


        $tmp = (object)$data;


        $tmp->level_2 = $data["level_2"] ?? 0;
        $tmp->level_3 = $data["level_3"] ?? 0;
        $tmp->cashback_fire_percent = $data["cashback_fire_percent"] ?? 0;
        $tmp->cashback_fire_period = $data["cashback_fire_period"] ?? 0;
        $tmp->max_cashback_use_percent = $data["max_cashback_use_percent"] ?? 0;
        $tmp->cashback_config = isset($data["cashback_config"]) ? json_decode($data["cashback_config"] ?? '[]') : null;
        $tmp->auto_cashback_on_payments = ($data["auto_cashback_on_payments"] ?? false) == "true";

        $warnings = null;
        if (isset($data["warnings"])) {
            $warnings = json_decode($data["warnings"]);
            unset($tmp->warnings);
        }

        $this->bot->update((array)$tmp);

        if (!is_null($warnings))
            foreach ($warnings as $warn) {

                $tmpWarn = BotWarning::query()
                    ->where("rule_key", $warn->rule_key)
                    ->where("bot_id", $this->bot->id)
                    ->first();

                if (!is_null($tmpWarn))
                    $tmpWarn->update([
                        'rule_key' => $warn->rule_key ?? null,
                        'rule_value' => $warn->rule_value ?? null,
                        'is_active' => $warn->is_active ?? false,
                    ]);
                else
                    $rez = BotWarning::query()->create([
                        'bot_id' => $this->bot->id,
                        'rule_key' => $warn->rule_key ?? null,
                        'rule_value' => $warn->rule_value ?? null,
                        'is_active' => $warn->is_active ?? false,
                    ]);
            }

        return new BotResource($this->bot);
    }


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function update(array $data, array $uploadedPhotos = null): BotResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
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

        if ($validator->fails())
            throw new ValidationException($validator);


        $company = Company::query()->where("id", $this->bot->company_id)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания не найдена");

        $photos = $this->uploadPhotos("/public/companies/$company->slug", $uploadedPhotos);


        $tmp = (object)$data;

        if (is_null($tmp->image))
            $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);

        $tmp->level_2 = $data["level_2"] ?? 0;
        $tmp->level_3 = $data["level_3"] ?? 0;
        $tmp->server = $data["server"] ?? null;
        $tmp->cashback_fire_percent = $data["cashback_fire_percent"] ?? 0;
        $tmp->cashback_fire_period = $data["cashback_fire_period"] ?? 0;
        $tmp->max_cashback_use_percent = $data["max_cashback_use_percent"] ?? 0;
        $tmp->message_threads = isset($data["message_threads"]) ? json_decode($data["message_threads"] ?? '[]') : null;
        $tmp->cashback_config = isset($data["cashback_config"]) ? json_decode($data["cashback_config"] ?? '[]') : null;
        $tmp->config = isset($data["config"]) ? json_decode($data["config"] ?? '[]') : null;
        $tmp->menu = isset($data["menu"]) ? json_decode($data["menu"] ?? '[]') : null;
        $tmp->commands = isset($data["commands"]) ? json_decode($data["commands"] ?? '[]') : null;
        $tmp->is_active = true;
        $tmp->auto_cashback_on_payments = $data["auto_cashback_on_payments"] == "true";
        $tmp->is_template = $data["is_template"] == "true";

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        //  $tmp->creator_id = $tmp->is_template ? null : ($tmp->creator_id ?? null);

        if (isset($data["keyboards"])) {
            unset($tmp->keyboards);
        }

        if (isset($data["slugs"])) {
            unset($tmp->slugs);
        }

        $warnings = null;
        if (isset($data["warnings"])) {
            $warnings = json_decode($data["warnings"]);
            unset($tmp->warnings);
        }

        unset($tmp->selected_bot_template_id);

        $this->bot->update((array)$tmp);

        if (!is_null($warnings))
            foreach ($warnings as $warn) {

                $tmpWarn = BotWarning::query()
                    ->where("rule_key", $warn->rule_key)
                    ->where("bot_id", $this->bot->id)
                    ->first();

                if (!is_null($tmpWarn))
                    $tmpWarn->update([
                        'rule_key' => $warn->rule_key ?? null,
                        'rule_value' => $warn->rule_value ?? null,
                        'is_active' => $warn->is_active ?? false,
                    ]);
                else
                    $rez = BotWarning::query()->create([
                        'bot_id' => $this->bot->id,
                        'rule_key' => $warn->rule_key ?? null,
                        'rule_value' => $warn->rule_value ?? null,
                        'is_active' => $warn->is_active ?? false,
                    ]);
            }

        return new BotResource($this->bot);
    }

    public function updateWebHookAndConfig(): void
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        if (env("APP_DEBUG") === false) {
            BotManager::bot()->setWebhooks();

            $this->prepareBaseBotConfig();
        }
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     * @deprecated
     */
    public function updateDeprecated(array $data, array $uploadedPhotos = null): BotResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
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

        if ($validator->fails())
            throw new ValidationException($validator);


        $company = Company::query()->where("id", $this->bot->company_id)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания не найдена");

        if (isset($data["removed_keyboards"])) {

            $tmpKeyboards = json_decode($data["removed_keyboards"]);

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

        if (isset($data["removed_slugs"])) {

            $tmpSlugs = json_decode($data["removed_slugs"]);

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

        $photos = $this->uploadPhotos("/public/companies/$company->slug", $uploadedPhotos);

        $botType = BotType::query()->where("slug", "cashback")->first();

        $tmp = (object)$data;

        if (is_null($tmp->image))
            $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);

        $tmp->level_2 = $data["level_2"] ?? 0;
        $tmp->level_3 = $data["level_3"] ?? 0;
        $tmp->cashback_fire_percent = $data["cashback_fire_percent"] ?? 0;
        $tmp->cashback_fire_period = $data["cashback_fire_period"] ?? 0;
        $tmp->message_threads = isset($data["message_threads"]) ? json_decode($data["message_threads"] ?? '[]') : null;
        $tmp->cashback_config = isset($data["cashback_config"]) ? json_decode($data["cashback_config"] ?? '[]') : null;

        $tmp->commands = isset($data["commands"]) ? json_decode($data["commands"] ?? '[]') : null;

        $tmp->bot_type_id = $botType->id;
        $tmp->is_active = true;
        $tmp->auto_cashback_on_payments = $data["auto_cashback_on_payments"] == "true";
        $tmp->is_template = $data["is_template"] == "true";

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $keyboards = null;
        if (isset($data["keyboards"])) {
            $keyboards = json_decode($data["keyboards"]);
            unset($tmp->keyboards);
        }

        $slugs = null;

        if (isset($data["slugs"])) {
            $slugs = json_decode($data["slugs"]);
            unset($tmp->slugs);
        }

        $warnings = null;
        if (isset($data["warnings"])) {
            $warnings = json_decode($data["warnings"]);
            unset($tmp->warnings);
        }

        unset($tmp->selected_bot_template_id);

        //dd($tmp);

        $this->bot->update((array)$tmp);

        if (!is_null($slugs))
            foreach ($slugs as $slug) {
                $slugId = $slug->id ?? null;

                $tmpSlug = !is_null($slugId) ? BotMenuSlug::query()
                    ->where("id", $slugId)
                    ->where("command", $slug->command)
                    ->where("slug", $slug->slug)
                    ->first() : null;

                if (!is_null($tmpSlug))
                    $tmpSlug->update([
                        'command' => $slug->command,
                        'comment' => $slug->comment,
                        'slug' => $slug->slug,
                        'is_global' => $slug->is_global ?? false,
                        'config' => $slug->config ?? null,
                    ]);
                else
                    BotMenuSlug::query()->create([
                        'bot_id' => $this->bot->id,
                        'command' => $slug->command,
                        'comment' => $slug->comment,
                        'slug' => $slug->slug,
                        'is_global' => $slug->is_global ?? false,
                        'config' => $slug->config ?? null,
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
                        'bot_id' => $this->bot->id,
                        'type' => $keyboard->type,
                        'slug' => $keyboard->slug,
                        'menu' => $keyboard->menu,
                    ]);
            }


        if (!is_null($warnings))
            foreach ($warnings as $warn) {


                $tmpWarn = BotWarning::query()
                    ->where("rule_key", $warn->rule_key)
                    ->where("bot_id", $this->bot->id)
                    ->first();

                if (!is_null($tmpWarn))
                    $tmpWarn->update([
                        'rule_key' => $warn->rule_key ?? null,
                        'rule_value' => $warn->rule_value ?? null,
                        'is_active' => $warn->is_active ?? false,
                    ]);
                else
                    $rez = BotWarning::query()->create([
                        'bot_id' => $this->bot->id,
                        'rule_key' => $warn->rule_key ?? null,
                        'rule_value' => $warn->rule_value ?? null,
                        'is_active' => $warn->is_active ?? false,
                    ]);
            }

        if (env("APP_DEBUG") === false) {
            BotManager::bot()->setWebhooks();

            $this->prepareBaseBotConfig();
        }

        return new BotResource($this->bot);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function updateByManager(array $data, array $uploadedPhotos = null): BotResource
    {
        if (is_null($this->botUser))
            throw new HttpException(404, "Не выполнены условия функции");

        $validator = Validator::make($data, [
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

        if ($validator->fails())
            throw new ValidationException($validator);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $data["id"])
            ->whereHas("company", function ($q) {
                $q->where("creator_id", $this->botUser->id);
            })
            ->first();

        if (is_null($bot))
            throw new HttpException(404, "Бот не найдена");

        if (isset($data["removed_keyboards"])) {

            $tmpKeyboards = json_decode($data["removed_keyboards"]);

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

        if (isset($data["removed_slugs"])) {

            $tmpSlugs = json_decode($data["removed_slugs"]);

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

        $photos = $this->uploadPhotos("/public/companies/" . $bot->company->slug, $uploadedPhotos);

        $botType = BotType::query()
            ->where("slug", "cashback")
            ->first();

        $tmp = (object)$data;

        if (is_null($tmp->image))
            $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);

        $tmp->level_2 = $data["level_2"] ?? 0;
        $tmp->level_3 = $data["level_3"] ?? 0;
        $tmp->bot_type_id = $botType->id;
        $tmp->is_active = true;
        $tmp->auto_cashback_on_payments = $data["auto_cashback_on_payments"] == "true";
        $tmp->is_template = $data["is_template"] == "true";

        $tmp->social_links = json_decode($tmp->social_links ?? '[]');

        $keyboards = null;
        if (isset($data["keyboards"])) {
            $keyboards = json_decode($data["keyboards"]);
            unset($tmp->keyboards);
        }

        $slugs = null;

        if (isset($data["slugs"])) {
            $slugs = json_decode($data["slugs"]);
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
                        'is_global' => $slug->is_global ?? false,
                        'config' => $slug->config ?? null,
                    ]);
                else
                    BotMenuSlug::query()->create([
                        'bot_id' => $this->bot->id,
                        'command' => $slug->command,
                        'comment' => $slug->comment,
                        'slug' => $slug->slug,
                        'is_global' => $slug->is_global ?? false,
                        'config' => $slug->config ?? null,
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
                        'bot_id' => $this->bot->id,
                        'type' => $keyboard->type,
                        'slug' => $keyboard->slug,
                        'menu' => $keyboard->menu,
                    ]);
            }


        if (env("APP_DEBUG") === false)
            BotManager::bot()->setWebhooks();

        return new BotResource($bot);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function createOrUpdateImageMenu(array $data, $uploadedPhoto = null): ImageMenuResource
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'title' => "required|string:255",
            'description' => "required|string:255",
            'bot_id' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        if (isset($data["deleted_menus"])) {

            $deleteds = json_decode($data["deleted_menus"]);

            foreach ($deleteds as $id) {
                $dmenu = ImageMenu::query()->find($id);
                if (!is_null($dmenu))
                    $dmenu->delete();
            }

        }

        $imageName = $data["image"] ?? null;

        if (!is_null($uploadedPhoto))
            $imageName = $this->uploadPhoto("/public/companies/" . $this->bot->company->slug, $uploadedPhoto);


        $tmp = (object)$data;

        $tmp->image = $imageName;
        $tmp->product_count = $data["product_count"] ?? 0;

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


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function uploadFilesFromForm(array $data,
                                        array $uploadedPhotos = null,
                                        array $uploadedDocuments = null,
                                        array $uploadedVideos = null): void
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не хватает критериев функции!");


        $channel = $this->bot->order_channel ?? null;

        $slug = $this->bot->company->slug;

        $photos = $this->uploadFiles("/public/companies/$slug", $uploadedPhotos);
        $documents = $this->uploadFiles("/public/companies/$slug", $uploadedDocuments);
        $videos = $this->uploadFiles("/public/companies/$slug", $uploadedVideos);

        $isSend = false;
        $content = $data["message"] ?? null;
        if (count($photos) > 1) {

            $media = [];
            foreach ($photos as $image) {
                $media[] = [
                    "media" => env("APP_URL") . "/images-by-bot-id/" . $this->bot->id . "/" . $image,
                    "type" => "photo",
                    "caption" => "$image"
                ];
            }

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMediaGroup($channel, json_encode($media));

            if (!is_null($content))
                BotMethods::bot()
                    ->whereBot($this->bot)
                    ->sendMessage($channel, $content);


            $isSend = true;
        }


        if (count($photos) == 1) {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendPhoto($channel, $content ?? null,
                    InputFile::create(storage_path("app/public") . "/companies/" . $slug . "/" . $photos[0])
                );
            $isSend = true;
        }

        if (count($videos) == 1) {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendVideo($channel, !$isSend ? $content : null,
                    InputFile::create(storage_path("app/public") . "/companies/" . $slug . "/" . $videos[0])
                );
            $isSend = true;
        }


        if (count($documents) == 1) {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendDocument($channel, !$isSend ? $content : null,
                    InputFile::create(storage_path("app/public") . "/companies/" . $slug . "/" . $documents[0])
                );

            $isSend = true;
        }


        if (!is_null($content) && !$isSend)
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage($channel, $content);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage($this->botUser->telegram_chat_id, "Файлы успешно загружены!");
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function sendToChannel(array $data, array $uploadedPhotos = null): void
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "text" => "required",
            "inline_keyboard" => "",
            "channel" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $channel = $data["channel"];


        $inlineKeyboard = json_decode($data["inline_keyboard"] ?? '[]');

        /*    $inlineKeyboard[] = [
                [
                    "text" => "🤖Вернуться в бота",
                    "url" => "https://t.me/" . $this->bot->bot_domain
                ]
            ];*/

        //dd($inlineKeyboard);

        $content = str_replace(["<p>", "</p>"], "", $data["text"]);
        $content = str_replace(["<br>"], "\n", $content);

        $slug = $this->bot->company->slug;

        $photos = $this->uploadPhotos("/public/companies/$slug", $uploadedPhotos);

        if (count($photos) > 1) {

            $media = [];
            foreach ($photos as $image) {

                $media[] = [
                    "media" => env("APP_URL") . "/images-by-bot-id/" . $this->bot->id . "/" . $image,
                    "type" => "photo",
                    "caption" => "$image"
                ];
            }

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMediaGroup($channel, json_encode($media))
                ->sendInlineKeyboard($channel, $content, $inlineKeyboard);


        } else if (count($photos) === 1) {

            if (mb_strlen($content) >= 1024)

                BotMethods::bot()
                    ->whereBot($this->bot)
                    ->sendMessage($channel, $content);

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendPhoto($channel, mb_strlen($content) >= 1024 ? null : $content,
                    InputFile::create(storage_path("app/public") . "/companies/" . $slug . "/" . $photos[0]),
                    $inlineKeyboard
                );

        } else if (count($photos) === 0)
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard($channel, $content, $inlineKeyboard);


    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function sendToCroneQueue(array $data): QueueResource
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "message" => "required",
            "inline_keyboard" => "",
            "images" => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $id = $data["id"] ?? null;
        $tmp = [
            "bot_id" => $this->bot->id,
            "content" => $data["message"] ?? 'Текст сообщения',
            "inline_keyboard" => $data["inline_keyboard"] ?? null,
            "reply_keyboard" => $data["reply_keyboard"] ?? null,
            "images" => json_decode($data["images"] ?? '[]'),
            "videos" => $data["videos"] ?? null,
            "audios" => $data["audios"] ?? null,
            "cron_time" => is_null($data["cron_time"] ?? null) ? null : Carbon::parse($data["cron_time"])
                ->setTimezone("+0:00")
                ->subHours(3) ?? null,
        ];

        if (is_null($id))
            $queue = Queue::query()
                ->create($tmp);
        else {
            $queue = Queue::query()->find($id);

            $queue->update($tmp);
        }

        return new QueueResource($queue);

    }


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function sendToRedisQueue(array $data): void
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "message" => "required",
            "inline_keyboard" => "",
            "images" => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $images = json_decode($data["images"] ?? '[]');/*   $tmp = [
            "images" => ,
            "videos" => $data["videos"] ?? null,
            "audios" => $data["audios"] ?? null,

        ]*/;

        $botUsers = BotUser::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        $delay = 0;
        foreach ($botUsers as $botUser) {
            if (count($images ?? []) == 0)
                $this->getPendingDispatch($botUser, $data, $delay);

            if (count($images ?? []) == 1)
                SendPhotoJob::dispatch(
                    botId: $this->bot->id,
                    chatId: $botUser->telegram_chat_id,
                    caption: $data["message"] ?? 'Текст сообщения',
                    photo: $images[0],
                    replyKeyboard: $data["reply_keyboard"] ?? null,
                    inlineKeyboard: $data["inline_keyboard"] ?? null,
                    messageThreadId: null,
                    keyboardSettings: $data["settings"] ?? null,
                )
                    ->delay((is_null($data["cron_time"] ?? null) ?
                        Carbon::now() :
                        Carbon::parse($data["cron_time"]))
                        ->setTimezone("+0:00")
                        ->subHours(3)
                        ->addSeconds($delay + 1)
                    );

            if (count($images ?? []) > 1) {

                $media = [];
                foreach ($images as $image) {
                    $media[] = [
                        "media" => $image,
                        "type" => "photo",
                        "caption" => "$image"
                    ];
                }

                SendMediaJob::dispatch(
                    botId: $this->bot->id,
                    chatId: $botUser->telegram_chat_id,
                    meida: json_encode($media),
                    messageThreadId: null,
                )
                    ->delay((is_null($data["cron_time"] ?? null) ?
                        Carbon::now() :
                        Carbon::parse($data["cron_time"]))
                        ->setTimezone("+0:00")
                        ->subHours(3)
                        ->addSeconds($delay + 1)
                    );

                $this->getPendingDispatch($botUser, $data, $delay);
            }


            $delay += 2;
        }


    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function switchBotStatus(): void
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $this->bot->is_active = !$this->bot->is_active;
        $this->bot->save();
    }

    /**
     * @throws HttpException
     */
    public function cashbackList(): CashBackCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $cashback = CashBack::query()
            ->with(["bot", "user", "user.botUser"])
            ->where("bot_id", $this->bot->id)
            ->get();

        return new CashBackCollection($cashback);
    }

    /**
     * @throws HttpException
     */
    public function cashbackHistoryList($orderBy = "user_id", $direction = "DESC"): CashBackHistoryCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $acceptFields = [
            'money_in_check',
            'description',
            'operation_type',
            'user_id',
            'bot_id',
            'amount',
            'level',
            'employee_id',
        ];

        if (!in_array($orderBy, $acceptFields))
            throw new HttpException(400, "Поле сортировки указано неверно!");

        $history = CashBackHistory::query()
            ->with(["bot", "user", "user.botUser", "employee"])
            ->where("bot_id", $this->bot->id)
            ->orderBy($orderBy, ($direction == "DESC" || $direction == "ASC" ? $direction : "DESC"))
            ->get();

        return new CashBackHistoryCollection($history);
    }


    /**
     * @throws HttpException
     */
    public function exportCashBackHistory($orderBy = "user_id", $direction = "DESC"): void
    {

        if (is_null($this->botUser))
            throw new HttpException(404, "Пользователь бота не найден!");

        $statistics = $this->cashbackHistoryList($orderBy, $direction);

        $name = Str::uuid();

        $date = Carbon::now()->format("Y-m-d H-i-s");

        Excel::store(new BotCashBackExport($statistics), "$name.xls", "public", \Maatwebsite\Excel\Excel::XLSX);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument($this->botUser->telegram_chat_id,
                "Статистика CashBack в боте",
                InputFile::create(
                    storage_path("app/public") . "/$name.xls",
                    "cashback-history-$date.xls"
                )
            );

        unlink(storage_path("app/public") . "/$name.xls");
    }

    /**
     * @param mixed $botUser
     * @param array $data
     * @param int $delay
     * @return void
     */
    private function getPendingDispatch(mixed $botUser, array $data, int $delay): void
    {
        SendMessageJob::dispatch(
            botId: $this->bot->id,
            chatId: $botUser->telegram_chat_id,
            message: $data["message"] ?? 'Текст сообщения',
            replyKeyboard: $data["reply_keyboard"] ?? null,
            inlineKeyboard: $data["inline_keyboard"] ?? null,
            messageThreadId: null,
            keyboardSettings: $data["settings"] ?? null,
        )
            ->delay((is_null($data["cron_time"] ?? null) ?
                Carbon::now() :
                Carbon::parse($data["cron_time"]))
                ->setTimezone("+0:00")
                ->subHours(3)
                ->addSeconds($delay)
            );
    }
}
