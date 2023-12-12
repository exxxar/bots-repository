<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\CashBackDirectionEnum;
use App\Enums\CustomFieldTypeEnum;
use App\Events\CashBackEvent;
use App\Events\CashBackSubEvent;
use App\Exports\BotStatisticExport;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\BusinessLogic\BusinessLogic;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\CashBackHistoryCollection;
use App\Http\Resources\CashBackHistoryResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\CustomField;
use App\Models\ManagerProfile;
use App\Models\Transaction;
use App\Models\YClients;
use Carbon\Carbon;
use Exception;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class BotAdministrativeLogicFactory
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

    public function setSlug($slug): static
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
    public function statistic(): array
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        if (!$this->botUser->is_admin)
            throw new HttpException(403, "Пользователь не является администратором");

        return [
            "users_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->count(),
            "users_in_bd_today" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "vip_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_vip", true)
                ->count(),
            "vip_in_bd_today" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_vip", true)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "admin_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_admin", true)
                ->count(),
            "work_admin_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_admin", true)
                ->where("is_work", true)
                ->count(),
            "summary_cashback" => CashBack::query()
                ->where("bot_id", $this->bot->id)
                ->sum("amount"),
            "summary_cashback_people_count" => CashBack::query()
                ->where("bot_id", $this->bot->id)
                ->count(),
            "cashback_day_up" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "cashback_day_up_level_1" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->where("level", 1)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_level_1_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->where("level", 1)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "cashback_day_up_level_2" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->where("level", 2)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_level_2_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->where("level", 2)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "cashback_day_up_level_3" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->where("level", 3)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_level_3_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->where("level", 3)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "cashback_day_down" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 0)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_down_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 0)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "cashback_summary_up" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->count(),
            "cashback_summary_up_level_1" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_level_1_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->count(),
            "cashback_summary_up_level_2" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_level_2_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->count(),
            "cashback_summary_up_level_3" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_level_3_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->count(),
            "cashback_summary_down" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 0)
                ->sum("amount"),
            "cashback_summary_down_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 0)
                ->count()
        ];
    }

    /**
     * @throws HttpException
     */
    public function exportBotStatistic(): void
    {
        $statistics = $this->statistic();

        $name = Str::uuid();

        $date = Carbon::now()->format("Y-m-d H-i-s");

        Excel::store(new BotStatisticExport($statistics), "$name.xls", "public", \Maatwebsite\Excel\Excel::XLSX);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument($this->botUser->telegram_chat_id,
                "Общая статистика бота",
                InputFile::create(
                    storage_path("app/public") . "/$name.xls",
                    "statistic-$date.xls"
                )
            );

        unlink(storage_path("app/public") . "/$name.xls");
    }


    /**
     * @throws HttpException
     */
    public function adminList($size = null): BotUserCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $size = $size ?? config('app.results_per_page');

        $botUsers = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $this->bot->id)
            //->where("is_work", true)
            ->where("is_admin", true)
            ->orderBy("is_work", "DESC")
            ->paginate($size);


        return new BotUserCollection($botUsers);
    }

    /**
     * @throws ValidationException
     */
    public function requestCashBack(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $tmp_user_id = (string)$data["user_telegram_chat_id"];

        $code = base64_encode("001$tmp_user_id");

        $url_link = "https://t.me/" . $this->bot->bot_domain . "?start=$code";

        $adminBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["admin_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        if ($adminBotUser->is_work) {
            $name = BotMethods::prepareUserName($userBotUser);

            $phone = $userBotUser->phone ?? null;

            $text =
                ("Пользователь <b> $name</b> запросил у вас начисление\списание кэшбэка") .
                (is_null($phone) ? "" : "\nНомер телефона для связи: <b>$phone</b>\n");

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard(
                    $adminBotUser->telegram_chat_id,
                    $text,
                    [
                        [
                            ["text" => "Действие с пользователем", "url" => "$url_link"]
                        ]
                    ],
                );


            $name = BotMethods::prepareUserName($adminBotUser);

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $userBotUser->telegram_chat_id,
                    "Администратор <b>$name</b> получил ваш запрос на зачисление CashBack"
                );

        } else {
            $name = BotMethods::prepareUserName($adminBotUser);

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $userBotUser->telegram_chat_id,
                    "Администратор <b>$name</b> на текущий момент не доступен! Попробуйте позже или же обратитесь к другому администратору!",
                );
        }
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addCashBack(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "amount" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $adminBotUser = $this->botUser ?? null;

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $percent = $data["percent"] ?? null;

        if (is_null($userBotUser) || is_null($adminBotUser))
            throw new HttpException(404, "Пользователь не найден");

        if (!is_null($data["category"] ?? null)) {
            /*   BotMethods::bot()
                   ->whereBot($this->bot)
                   ->sendMessage($adminBotUser->telegram_chat_id, "Попытка добавить CashBack в категорию. Данная возможность еще в разработке.");*/
            event(new CashBackSubEvent(
                $data["category"],
                (int)$this->bot->id,
                (int)$userBotUser->user_id,
                (int)$adminBotUser->user_id,
                ((float)$data["amount"] ?? 0),
                $data["info"],
                CashBackDirectionEnum::Crediting,
                $percent
            ));
            return;
        }


        event(new CashBackEvent(
            (int)$this->bot->id,
            (int)$userBotUser->user_id,
            (int)$adminBotUser->user_id,
            ((float)$data["amount"] ?? 0),
            $data["info"],
            CashBackDirectionEnum::Crediting,
            $percent
        ));
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function removeCashBack(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "amount" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден");

        event(new CashBackEvent(
            (int)$this->bot->id,
            (int)$userBotUser->user_id,
            (int)$adminBotUser->user_id,
            ((float)$data["amount"] ?? 0),
            $data["info"],
            CashBackDirectionEnum::Debiting

        ));

    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function sendPageToUser(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "page_id" => "required|integer",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $info = $data["info"] ?? '-';
        $pageId = $data["page_id"];

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $page = BotPage::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", "$pageId")
            ->first();

        if (is_null($page))
            throw new HttpException(404, "Страница не найден");

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден");


        $name = BotMethods::prepareUserName($userBotUser);

        BotManager::bot()
            ->setBot($this->bot)
            ->pushPage($page->id, $userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $userBotUser->telegram_chat_id,
                "Комментарий:\n", $info)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы отправили страницу #$page->id пользователю $name:\n$info"
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function sendInvoice(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "amount" => "required|integer",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $info = $data["info"] ?? '-';
        $amount = ($data["amount"] ?? 100) * 100;

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден");

        $prices = [
            [
                "label" => "Счет на оплату",
                "amount" => $amount
            ]
        ];

        $payload = bin2hex(Str::uuid());

        $providerToken = $this->bot->payment_provider_token;
        $currency = "RUB";

        Transaction::query()->create([
            'user_id' => $userBotUser->user_id,
            'bot_id' => $this->bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $amount,
            'status' => 0,
            'products_info' => (object)[
                "payload" => $payloadData ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => true,
            "need_phone_number" => true,
            "need_email" => true,
            "need_shipping_address" => false,
            "send_phone_number_to_provider" => true,
            "send_email_to_provider" => true,
            "is_flexible" => false,
            "disable_notification" => false,
            "protect_content" => false,
        ];

        $taxSystemCode = $bot->company->vat_code ?? 1;

        $keyboard = [
            [
                ["text" => "Оплатить", "pay" => true],
            ],

        ];

        $providerData = (object)[
            "receipt" => [
                (object)[
                    "description" => "Счет на оплату",
                    "quantity" => "1.00",
                    "amount" => (object)[
                        "value" => $amount / 100,
                        "currency" => $currency
                    ],
                    "vat_code" => $taxSystemCode
                ]
            ]
        ];

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInvoice(
                $userBotUser->telegram_chat_id,
                "Счет на оплату", $info, $prices, $payload, $providerToken, $currency, $needs, $keyboard, $providerData)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы отправили счет на оплату пользователю $name:\n" . ($data["amount"] ?? 100) . "руб\n$info"
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function sendApprove(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "info" => "required",
            "action_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $info = $data["info"] ?? '-';

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;


        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден");

        $action = ActionStatus::query()->find($data["action_id"]);;

        $tmp = [];
        foreach (($action->data ?? []) as $item) {
            $item["answered_at"] = Carbon::now();
            $item["answered_by"] = BotMethods::prepareUserName($adminBotUser);

            $tmp[] = $item;
        }
        $action->data = $tmp;
        $action->save();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $userBotUser->telegram_chat_id,
                "Информация от администратора:\n$info"
            );

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы успешно отправили сообщение для <b>$name</b>",
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addAdmin(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $info = $data["info"] ?? '-';

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден");

        $userBotUser->is_admin = true;
        $userBotUser->save();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $userBotUser->telegram_chat_id,
                "Вас назначили администратором данного бота!Повторно запустите команду /start:\n$info"
            );


        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы успешно назанчили администратором <b>$name</b>",
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function removeAdmin(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $info = $data["info"] ?? '-';

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден");

        $userBotUser->is_admin = false;
        $userBotUser->save();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $userBotUser->telegram_chat_id,
                "Вас разжаловали с должности администратора, теперь вам недоступны административные возможности:\n$info"
            );

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы успешно убрали статус администратора у пользовтеля <b>$name</b>",
            );

    }

    /**
     * @throws HttpException
     */
    public function selfRemoveAdmin(): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $adminBotUser = $this->botUser;
        $adminBotUser->is_admin = false;
        $adminBotUser->save();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вас разжаловали с должности администратора, теперь вам недоступны административные возможности"
            );

    }

    /**
     * @throws HttpException
     */
    public function workStatus(): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $adminBotUser = $this->botUser;
        $adminBotUser->is_work = !$adminBotUser->is_work;
        $adminBotUser->save();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы изменили свой рабочий статус на <b>" . ($adminBotUser->is_work ? "Работаю" : "Не работаю") . "</b>." .
                ($adminBotUser->is_work ? "Теперь вас МОГУТ выбирать для работы с CashBack" : "Теперь вас НЕ могут выбирать для работы с CashBack"),
            );

    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function vipStore(array $data): void
    {

        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            //"birthday" => "required",
            //"city" => "required",
            "sex" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $firstCashBackGranted = (Collection::make($this->slug->config)
            ->where("key", "first_cashback_granted")
            ->first())["value"] ?? null;

        $needFailMessage = (Collection::make($this->slug->config)
            ->where("key", "first_cashback_need_fail_message")
            ->first())["value"] ?? false;


        $birthday = Carbon::parse($data["birthday"] ?? Carbon::now())->format("Y-m-d");
        $form = [
            "birthday" => $birthday,
            "name" => $data["name"] ?? null,
            "phone" => $data["phone"] ?? null,
            "city" => $data["city"] ?? null,
            "country" => $data["country"] ?? null,
            "address" => $data["address"] ?? null,
            "sex" => ($data["sex"] ?? false) == "on" ? 1 : 0,
            "email" => $data["email"] ?? null,
            "is_vip" => true,
            "age" => Carbon::now()->year - Carbon::parse($birthday)
                    ->year
        ];

        $this->botUser->update($form);

        $fields = $data["fields"] ?? null;//isset($data["fields"]) ? json_decode($data["fields"] ?? '[]') : null;

        if (!is_null($fields)) {
            foreach ($fields as $field) {
                $field = (object)$field;
                $value = null;

                switch ($field->type) {
                    default:
                    case CustomFieldTypeEnum::Text:
                        $value = $field->value;
                        break;
                    case CustomFieldTypeEnum::Number:
                        $value = floatval($field->value);
                        break;
                    case CustomFieldTypeEnum::Boolean:
                        $value = (bool)$field->value;
                        break;
                }

                $res = CustomField::query()->updateOrCreate([
                    'bot_user_id' => $this->botUser->id,
                    'bot_custom_field_setting_id' => $field->id,
                ], [
                    'value' => $value
                ]);
            }
        }

        $yClients = YClients::query()
            ->where("bot_id",$this->bot->id)
            ->first();

        if (!is_null($yClients)) {
            try {
                \App\Facades\BusinessLogic::yClients()
                    ->setBot($this->bot)
                    ->createClient([
                        "name" => $data["name"] ?? null,
                        "phone" => $data["phone"] ?? null,
                        "email" => $data["email"] ?? null,
                        "sex_id" => ($data["sex"] ?? false) == "on" ? 1 : 2,
                        "birth_date" => $birthday ?? null,
                        "comment" => "Прохождение VIP-анкетирования в боте",
                    ]);
            } catch (Exception $exception) {
                Log::info("Ошибка создания клиента YClients");
                Log::info($exception);
            }

        }

        if (!is_null($firstCashBackGranted)) {
            $adminBotUser = BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_admin", true)
                ->orderBy("updated_at", "desc")
                ->first();

            if (!is_null($adminBotUser) && $firstCashBackGranted > 0)
                event(new CashBackEvent(
                    (int)$this->bot->id,
                    (int)$this->botUser->user_id,
                    (int)$adminBotUser->user_id,
                    $firstCashBackGranted,
                    "Начисление CashBack за прохождение анкеты",
                    CashBackDirectionEnum::Crediting,
                    100
                ));
            else {

                if ($needFailMessage)
                    BotMethods::bot()
                        ->whereBot($this->bot)
                        ->sendMessage(
                            $this->botUser->telegram_chat_id,
                            "Сейчас, к сожалению, нет администратора, но когда он появится вы сможете получить дополнительно <strong>$firstCashBackGranted руб.</strong> кэшбэка"
                        );
            }
        }


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Вы стали нашим <b>V.I.P.</b> пользователем! Поздравляем!"
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function messageToUser(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $info = $data["info"] ?? '-';

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден!");

        //  $userBotUser->user_in_location = true;
        // $userBotUser->location_comment = $data["info"] ?? null;
        // $userBotUser->save();

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $userBotUser->telegram_chat_id,
                "Вам прислали сообщение:\n$info\n<em>Для ответа наберите текст БОЛЬШЕ 10 символов в боте и отправьте.</em>"
            )
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы отправили пользователяю $name в сообщение."
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function requestUserData(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $info = $data["info"] ?? '-';

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден!");

        $userBotUser->is_vip = false;
        $userBotUser->save();

        $name = BotMethods::prepareUserName($userBotUser);

        $slug = BotMenuSlug::query()
            ->where("slug", "global_cashback_main")
            ->where("bot_id", $this->bot->id)
            ->orderBy("created_at", "desc")
            ->first();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $userBotUser->telegram_chat_id,
                "Вам отправили запрос на ввод пользовательских данных с сообщением:\n$info",
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/" . $this->bot->bot_domain . "?slug=" . ($slug->id ?? "route") . "#/vip"
                        ]],
                    ],

                ]
            )
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы отправили пользователю $name запрос на ввод данных."
            );

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function requestRefreshMenu(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $info = $data["info"] ?? '-';

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $adminBotUser = $this->botUser;

        if (is_null($userBotUser))
            throw new HttpException(404, "Пользователь не найден!");

        $userBotUser->is_vip = false;
        $userBotUser->save();

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $userBotUser->telegram_chat_id,
                "Вам отправили запрос на обновление главного меню с сообщением:\n$info",
                [
                    [
                        ["text" => "Обновить главное меню", "callback_data" => "/start"],
                    ],

                ]
            )
            ->sendReplyKeyboard(
                $userBotUser->telegram_chat_id,
                "Обновить меню",
                [
                    [
                        ["text" => "Главное меню"],
                    ],

                ]
            )
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы отправили пользователю $name запрос на обновление меню."
            );

    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function cashBackHistoryList(array $data, $size = null): CashBackHistoryCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $size = $size ?? config('app.results_per_page');

        $botUser = BotUser::query()
            ->where("bot_id", $this->bot->id)
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->first();

        if (is_null($botUser))
            throw new HttpException(404, "Пользователь не найден!");

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $this->bot->id)
            ->where("user_id", $botUser->user_id)
            ->orderBy("created_at", "desc")
            ->paginate($size);

        return new CashBackHistoryCollection($cashBackHistories);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function cashbackReceiver(array $data): BotUserResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "user_telegram_chat_id" => "required"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $botUser = BotUser::query()
            ->where("bot_id", $this->bot->id)
            ->where("telegram_chat_id", $data["user_telegram_chat_id"])
            ->first();

        if (is_null($botUser))
            throw new HttpException(404, "Пользователь не найден!");

        return new BotUserResource($botUser);
    }


}
