<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotDialogGroupStoreRequest;
use App\Http\Requests\BotDialogGroupUpdateRequest;
use App\Http\Resources\BotDialogCommandResource;
use App\Http\Resources\BotDialogGroupCollection;
use App\Http\Resources\BotDialogGroupResource;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotDialogGroup;
use App\Models\BotDialogResult;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BotDialogsController extends Controller
{
    public function index(Request $request)
    {

        $bot = $request->bot;

        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;


        $botDialogGroups = BotDialogGroup::query()
            ->where("bot_id", $bot->id);


        if (!is_null($search))
            $botDialogGroups = $botDialogGroups
                ->where("slug", 'like', "%$search%");

        $botDialogGroups = $botDialogGroups
            ->orderBy("created_at","desc")
            ->paginate($size);


        return BotDialogGroupResource::collection($botDialogGroups);
    }

}
