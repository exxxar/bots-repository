<?php

namespace App\Http\BusinessLogic\Methods;

use App\Exports\BotStatisticExport;
use App\Exports\BotUsersExport;
use App\Facades\BotMethods;
use App\Http\Resources\ActionStatusCollection;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Models\ActionStatus;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class BotUserLogicFactory
{
    protected $bot;
    protected $botUser;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;

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
    public function setBotUser($botUser = null): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    public function all($needAdmins = false): BotUserCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $botUsers = BotUser::query()
            ->with(["bot", "cashBack"])
            ->where("bot_id", $this->bot->id);

        if ($needAdmins)
            $botUsers = $botUsers
                ->where("is_admin", $needAdmins);

        $botUsers = $botUsers
            ->orderBy("created_at", "DESC")
            ->get();

        return new BotUserCollection($botUsers);
    }


    public function exportBotUsers($needAdmins = false): void
    {
        if (is_null($this->botUser))
            throw new HttpException(404, "Пользователь бота не найден!");

        $statistics = $this->all($needAdmins);

        $name = Str::uuid();

        $date = Carbon::now()->format("Y-m-d H-i-s");

        Excel::store(new BotUsersExport($statistics), "$name.xls", "public");

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument($this->botUser->telegram_chat_id,
                "Пользователи бота с CashBack",
                InputFile::create(
                    storage_path("app/public") . "/$name.xls",
                    "bot-users-$date.xls"
                )
            );

        unlink(storage_path("app/public") . "/$name.xls");
    }


    /**
     * @throws HttpException
     */
    public function list($search = null, $size = null, array $params = null): BotUserCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");


        $size = $size ?? config('app.results_per_page');


        $botUsers = BotUser::query()
            ->where("bot_id", $this->bot->id);

        if (!is_null($search)) {
            $botUsers = $botUsers
                ->where(function ($q) use ($search) {
                    $q->orWhere("name", 'like', "%$search%")
                        ->orWhere("phone", 'like', "%$search%")
                        ->orWhere("fio_from_telegram", 'like', "%$search%")
                        ->orWhere("telegram_chat_id", 'like', "%$search%");
                });

        }

        $needAdmins = $params["need_admins"] ?? false;
        $needVip = $params["need_vip"] ?? false;
        $needNotVip = $params["need_not_vip"] ?? false;
        $needDeliveryman = $params["need_deliveryman"] ?? false;
        $needWithPhone = $params["need_with_phone"] ?? false;
        $needWithoutPhone = $params["need_without_phone"] ?? false;

        if ($needAdmins)
            $botUsers = $botUsers
                ->where("is_admin", true);


        if ($needDeliveryman)
            $botUsers = $botUsers
                ->where("is_deliveryman", true);

        if ($needVip)
            $botUsers = $botUsers
                ->where("is_vip", true);

        if ($needNotVip)
            $botUsers = $botUsers
                ->where("is_vip", false);

        if ($needWithPhone)
            $botUsers = $botUsers
                ->whereNotNull("phone");

        if ($needWithoutPhone)
            $botUsers = $botUsers
                ->whereNull("phone");

        $botUsers = $botUsers
            ->orderBy("created_at", "DESC")
            ->paginate($size);

        return new BotUserCollection($botUsers);
    }

    /**
     * @throws HttpException
     */
    public function actionStatusHistoryList($event = "event", $search = null, $size = null): ActionStatusCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");


        $size = $size ?? config('app.results_per_page');

        $actions = ActionStatus::query()
            ->with(["slug"])
            ->whereNotNull("data")
            ->where("bot_id", $this->bot->id);


        if (!is_null($search)) {

            if ($event == "event")
                $actions = $actions
                    ->whereHas("slug", function ($q) use ($search) {
                        $q->where("command", "like", "%$search%");
                    });

            if ($event == "users") {
                $userIds = BotUser::query()
                    ->where("name", "like", "%$search%")
                    ->orWhere("fio_from_telegram", "like", "%$search%")
                    ->get()
                    ->pluck("user_id")->toArray();

                $actions = $actions
                    ->orWhereIn("user_id", $userIds);
            }

            if ($event == "phone") {
                $userIds = BotUser::query()
                    ->where("phone", "like", "%$search%")
                    ->get()
                    ->pluck("user_id")->toArray();

                $actions = $actions
                    ->orWhereIn("user_id", $userIds);
            }

        }


        $actions = $actions
            ->orderBy("updated_at", "asc")
            ->paginate($size);


        return new ActionStatusCollection($actions);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function update(array $data): BotUserResource
    {
        $validator = Validator::make($data, [
            "id" => "required",
            "is_vip" => "required",
            "is_admin" => "required",
            "is_work" => "required",
            "user_in_location" => "required",
            "name" => "required",
            "phone" => "required",
            "email" => "",
            "birthday" => "",
            "city" => "",
            "country" => "",
            "address" => "",
            "sex" => "",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $botUser = BotUser::query()
            ->where("id", $data["id"])
            ->first();

        if (is_null($botUser))
            throw new HttpException(404, "Пользователь бота не найден");


        $birthday = Carbon::parse($data["birthday"] ?? $botUser->birthday ?? Carbon::now())->format("Y-m-d");


        $botUser->is_vip = (bool)(($data["is_vip"] ?? false));
        $botUser->is_admin = (bool)(($data["is_admin"] ?? false));
        $botUser->is_work = (bool)(($data["is_work"] ?? false));
        $botUser->is_manager = (bool)(($data["is_manager"] ?? false));
        $botUser->user_in_location = (bool)(($data["user_in_location"] ?? false));
        $botUser->name = $data["name"] ?? $botUser->name ?? null;
        $botUser->phone = $data["phone"] ?? $botUser->phone ?? null;
        $botUser->email = $data["email"] ?? $botUser->email ?? null;
        $botUser->birthday = $birthday;
        $botUser->city = $data["city"] ?? $botUser->city ?? null;
        $botUser->country = $data["country"] ?? $botUser->country ?? null;
        $botUser->address = $data["address"] ?? $botUser->address ?? null;
        $botUser->sex = (bool)(($data["sex"] ?? false));
        $botUser->age = Carbon::now()->year - Carbon::parse($birthday)
                ->year;
        $botUser->blocked_at = (bool)(($data["is_blocked"] ?? false)) ? Carbon::now() : null;
        $botUser->blocked_message = $data["blocked_message"] ?? null;
        $botUser->save();

        if (!is_null($botUser->blocked_at))
            return new BotUserResource($botUser);


        $message = sprintf("Ф.И.О: %s\nТелефон: %s\nПочта: %s\nДР: %s\nВозраст: %s\nСтрана: %s\nГород: %s\nАдрес: %s\nПол: %s\nVip: %s\nAdmin: %s\nЗа работой: %s\nМенеджер: %s",
            $botUser->name ?? "Не указано",
            $botUser->phone ?? "Не указано",
            $botUser->email ?? "Не указано",
            $botUser->birthday ?? "Не указано",
            $botUser->age ?? "Не указано",
            $botUser->country ?? "Не указано",
            $botUser->city ?? "Не указано",
            $botUser->address ?? "Не указано",
            $botUser->sex ? "муж" : "жен",
            $botUser->is_vip ? "да" : "нет",
            $botUser->is_admin ? "да" : "нет",
            $botUser->is_work ? "да" : "нет",
            $botUser->is_manager ? "да" : "нет",
        );
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $botUser->telegram_chat_id,
                "Ваши анкетные данные обновлены администратором:\n $message"
            );

        return new BotUserResource($botUser);
    }
}
