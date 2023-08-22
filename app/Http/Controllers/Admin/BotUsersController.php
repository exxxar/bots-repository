<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
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
    public function loadBotUsers(Request $request): BotUserCollection
    {
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->list($request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),
                ($request->need_admins ?? false) == "true");
    }

    public function loadActionStatusHistories(Request $request): ActionStatusCollection
    {
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->actionStatusHistoryList(
                $request->event ?? 'event',
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }
}
