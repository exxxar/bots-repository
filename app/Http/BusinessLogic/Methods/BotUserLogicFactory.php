<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\ActionStatusCollection;
use App\Http\Resources\BotUserCollection;
use App\Models\ActionStatus;
use App\Models\BotUser;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotUserLogicFactory
{
    protected $bot;

    public function __construct()
    {
        $this->bot = null;

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
    public function list($search = null, $size = null, $needAdmins = false): BotUserCollection
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
                });

        }

        if ($needAdmins)
            $botUsers = $botUsers
                ->where("is_admin", $needAdmins);

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

}
