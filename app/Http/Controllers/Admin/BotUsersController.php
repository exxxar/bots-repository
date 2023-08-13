<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusCategoryCollection;
use App\Http\Resources\ActionStatusCollection;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Models\ActionStatus;
use App\Models\BotUser;
use Illuminate\Http\Request;

class BotUsersController extends Controller
{
    public function loadBotUsers(Request $request)
    {
        $bot = $request->bot;

        $need_admins = $request->need_admins == "true";

        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;

        $botUsers = BotUser::query()
            ->where("bot_id", $bot->id);

        if (!is_null($search)) {
            $botUsers = $botUsers
                ->where(function ($q) use ($search) {
                    $q->orWhere("name", 'like', "%$search%")
                        ->orWhere("phone", 'like', "%$search%")
                        ->orWhere("fio_from_telegram", 'like', "%$search%");
                });

        }

        if ($need_admins)
            $botUsers = $botUsers
                ->where("is_admin", $need_admins);

        $botUsers = $botUsers
            ->orderBy("created_at", "DESC")
            ->paginate($size);

        return new BotUserCollection($botUsers);
    }

    public function loadActionStatusHistories(Request $request)
    {

        $search = $request->search ?? null;

        $event = $request->event ?? 'event';

        $size = $request->get("size") ?? config('app.results_per_page');

        $actions = ActionStatus::query()
            ->with(["slug"])
            ->whereNotNull("data")
            ->where("bot_id", $request->bot->id);


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
        /*
                    response()
                    ->json([
                        "actions"=> new ActionStatusCollection($actions),
                        "categories"=>  new ActionStatusCategoryCollection($categories)
                    ]);*/
    }
}
