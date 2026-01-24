<?php

namespace App\Http\BusinessLogic\Methods;

use App\Exports\BotStatisticExport;
use App\Exports\BotUsersExport;
use App\Facades\BotMethods;
use App\Http\Resources\ActionStatusCollection;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Models\ActionStatus;
use App\Models\Basket;
use App\Models\BotDialogResult;
use App\Models\BotMedia;
use App\Models\BotNote;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Documents;
use App\Models\Order;
use App\Models\ReferralHistory;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class BotUserLogicFactory extends BaseLogicFactory
{
    public function getUserProfilePhotos($botUserId = null): mixed
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Параметры не заданы!");

        $botUser = $this->botUser;
        if (!is_null($botUserId)) {
            $botUser = BotUser::query()
                ->where("id", $botUserId)
                ->where("bot_id", $this->bot->id)
                ->first();

            if (is_null($botUser))
                throw new HttpException(404, "Пользователь не найден!");
        }

        try {
            $client = Http::post("https://api.telegram.org/bot" . $this->bot->bot_token . "/getUserProfilePhotos", [
                "user_id" => $botUser->telegram_chat_id
            ]);

            $orders = Order::query()
                ->where("bot_id", $this->bot->id)
                ->where("customer_id", $botUser->id)
                ->count();

            $refCount = BotUser::query()
                ->where("parent_id", $botUser->id)
                ->count() ?? 0;

            $botUser->order_count = $orders;
            $botUser->friends_count = $refCount;
            $botUser->parent_friend = BotUser::query()
                ->find($botUser->parent_id) ?? null;

            return (object)[
                "photos" => $client->json(),
                "profile" => new BotUserResource($botUser)
            ];

        } catch (\Exception $e) {

        }

        return null;
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

        Excel::store(new BotUsersExport($statistics), "$name.xls", "public", \Maatwebsite\Excel\Excel::XLS);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument($this->botUser->telegram_chat_id,
                "Пользователи бота с бонусами",
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
    public function friends($search = null, $size = null): BotUserCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все условия функции выполнены!");


        $size = $size ?? config('app.results_per_page');

        $userIds = ReferralHistory::query()
            ->where("user_sender_id", $this->botUser->user_id)
            ->where("bot_id", $this->bot->id)
            ->pluck("user_recipient_id");

        $friends = BotUser::query()
            ->whereIn("user_id", $userIds)
            ->where("bot_id", $this->bot->id)
            ->orderBy("created_at", "desc")
            ->paginate($size);

        return new BotUserCollection($friends);
    }

    public function initCoffee(): array
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Параметры не соответствуют условию!");

        $config = $this->botUser->config ?? [];

        if (!isset($config["coffee"]))
            $config["coffee"] = (object)[
                "count" => 0
            ];

        $this->botUser->config = $config;
        $this->botUser->save();

        return $config["coffee"];
    }

    public function toggleProductInFavorites($id): array
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Параметры не соответствуют условию!");

        $config = $this->botUser->config ?? [];

        if (in_array($id, $config["favorites"] ?? [])) {
            $config["favorites"] = array_values(array_diff($config["favorites"], [$id]));
        } else {

            if (isset($config["favorites"]))
                $config["favorites"][] = $id;
            else
                $config["favorites"] = [$id];
        }

        $this->botUser->config = $config;
        $this->botUser->save();

        return $config["favorites"];
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
                        ->orWhere("fio_from_telegram", 'like', "%$search%");
                    //->orWhere("telegram_chat_id", 'like', "%$search%");
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
    public function resetAllBotUsers(): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Параметры не соответствуют условию!");

        if (!$this->botUser->is_admin)
            throw new HttpException(403, "Недостаточно прав!");


        Schema::disableForeignKeyConstraints();
        $medias = BotMedia::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($medias) > 0)
            foreach ($medias as $media)
                $media->forceDelete();

        $notes = BotNote::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($notes) > 0)
            foreach ($notes as $note)
                $note->forceDelete();

        $baskets = Basket::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($baskets) > 0)
            foreach ($baskets as $basket)
                $basket->forceDelete();

        $cashBacks = CashBack::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($cashBacks) > 0)
            foreach ($cashBacks as $cashBack)
                $cashBack->forceDelete();

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($cashBackHistories) > 0)
            foreach ($cashBackHistories as $history)
                $history->forceDelete();

        $documents = Documents::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($documents) > 0)
            foreach ($documents as $document)
                $document->forceDelete();

        $orders = Order::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($orders) > 0)
            foreach ($orders as $order)
                $order->forceDelete();


        $referrals = ReferralHistory::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($referrals) > 0)
            foreach ($referrals as $referral)
                $referral->forceDelete();

        $reviews = Review::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($reviews) > 0)
            foreach ($reviews as $review)
                $review->forceDelete();

        $transactions = Transaction::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($transactions) > 0)
            foreach ($transactions as $transaction)
                $transaction->forceDelete();

        $statuses = ActionStatus::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($statuses) > 0)
            foreach ($statuses as $status)
                $status->forceDelete();


        $botUsers = BotUser::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (count($botUsers) > 0)
            foreach ($botUsers as $botUser) {
                $dialogResults = BotDialogResult::query()
                    ->where("bot_user_id", $botUser->id)
                    ->get();

                if (count($dialogResults) > 0)
                    foreach ($dialogResults as $result)
                        $result->forceDelete();

                $botUser->forceDelete();
            }
        Schema::enableForeignKeyConstraints();

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
     * @throws ValidationException
     */
    public function updateProfile(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Параметры не соответствуют условию!");

        $validator = Validator::make($data, [
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


        $botUser = $this->botUser;

        $config = $botUser->config ?? [];

        $birthday = Carbon::parse($data["birthday"] ?? $botUser->birthday ?? Carbon::now())->format("Y-m-d");


        if (!is_null($data["phone"] ?? null)) {
            $vowels = ["(", ")", "-"];
            $filteredPhone = str_replace($vowels, "", $data["phone"]);
            $botUser->phone = $filteredPhone;
        } else
            $botUser->phone = $botUser->phone ?? null;

        $config["need_bot_mailing"] = (bool)(($data["config"]["need_bot_mailing"] ?? false));

        $botUser->name = $data["name"] ?? $botUser->name ?? null;
        $botUser->email = $data["email"] ?? $botUser->email ?? null;
        $botUser->birthday = $birthday;
        $botUser->city = $data["city"] ?? $botUser->city ?? null;
        $botUser->country = $data["country"] ?? $botUser->country ?? null;
        $botUser->address = $data["address"] ?? $botUser->address ?? null;
        $botUser->sex = (bool)(($data["sex"] ?? false));
        $botUser->age = Carbon::now()->year - Carbon::parse($birthday)
                ->year;

        $botUser->config = $config;
        $botUser->save();


        $message = sprintf("Ф.И.О: %s\nТелефон: %s\nПочта: %s\nДР: %s\nВозраст: %s\nСтрана: %s\nГород: %s\nАдрес: %s\nПол: %s\nРассылки: %s",
            $botUser->name ?? "Не указано",
            $botUser->phone ?? "Не указано",
            $botUser->email ?? "Не указано",
            $botUser->birthday ?? "Не указано",
            $botUser->age ?? "Не указано",
            $botUser->country ?? "Не указано",
            $botUser->city ?? "Не указано",
            $botUser->address ?? "Не указано",
            $botUser->sex ? "муж" : "жен",
            $config["need_bot_mailing"] ? "включены" : "отключены"
        );
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $botUser->telegram_chat_id,
                "Ваши анкетные данные обновлены:\n $message"
            );

        return new BotUserResource($botUser);
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

        $config = $botUser->config ?? [];

        $config["need_bot_mailing"] = (bool)(($data["config"]["need_bot_mailing"] ?? false));

        $birthday = Carbon::parse($data["birthday"] ?? $botUser->birthday ?? Carbon::now())->format("Y-m-d");

        if (!is_null($data["phone"] ?? null)) {
            $vowels = ["(", ")", "-"];
            $filteredPhone = str_replace($vowels, "", $data["phone"]);
            $botUser->phone = $filteredPhone;
        } else
            $botUser->phone = $botUser->phone ?? null;

        $botUser->is_vip = (bool)(($data["is_vip"] ?? false));
        $botUser->is_admin = (bool)(($data["is_admin"] ?? false));
        $botUser->is_work = (bool)(($data["is_work"] ?? false));
        $botUser->is_manager = (bool)(($data["is_manager"] ?? false));
        $botUser->user_in_location = (bool)(($data["user_in_location"] ?? false));
        $botUser->name = $data["name"] ?? $botUser->name ?? null;

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
        $botUser->config = $config;
        $botUser->save();

        if (!is_null($botUser->blocked_at))
            return new BotUserResource($botUser);


        $message = sprintf("Ф.И.О: %s\nТелефон: %s\nПочта: %s\nДР: %s\nВозраст: %s\nСтрана: %s\nГород: %s\nАдрес: %s\nПол: %s\nVip: %s\nAdmin: %s\nЗа работой: %s\nМенеджер: %s\nРассылки: %s",
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
            $config["need_bot_mailing"] ? "включены" : "отключены"
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
