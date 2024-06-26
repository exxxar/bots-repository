<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotMenuSlugStoreRequest;
use App\Http\Requests\BotMenuSlugUpdateRequest;
use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BotMenuSlugController extends Controller
{

    public function loadSlugsParams(Request $request)
    {
        $request->validate([
            "slug_id" => "required"
        ]);

        $slug = BotMenuSlug::query()
            ->find($request->slug_id);

        if (is_null($slug))
            return \response()->noContent(404);

        if (!is_null($slug->bot_id)){
            $slug = BotMenuSlug::query()
                ->where("slug", $slug->slug)
                ->whereNull("bot_id")
                ->first();

            if (is_null($slug))
                return \response()->noContent(404);
        }


        return \response()->json([
            "config" => $slug->config ?? null
        ]);
    }

    public function reloadGlobalScripts(Request $request)
    {
        try {
            Artisan::call("bot:reinit-scripts-configs");
            return \response()->noContent();
        } catch (\Exception $exception) {
            return \response()->noContent(400);
        }
    }

    public function actionDataExport(Request $request, $slugId)
    {
        $statuses = \App\Models\ActionStatus::query()
            ->where("slug_id", $slugId)->get();
        return Excel::download(new \App\Exports\ExportArrayData($statuses->toArray()), "action-statuses-data-$slugId.xlsx", \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * @throws ValidationException
     */
    public function relocateData(Request $request): Response
    {
        $request->validate([
            "bot_id" => "required",
            "slug_sender_id" => "required",
            "slug_recipient_id" => "required",
        ]);

        $bot = Bot::query()->find($request->bot_id);

        BusinessLogic::slugs()
            ->setBot($bot ?? null)
            ->relocateActionData((array)$request->all());

        return response()->noContent();
    }

    public function allSlugList(Request $request, $botId): BotMenuSlugCollection
    {
        $bot = Bot::query()->find($botId);

        return BusinessLogic::slugs()
            ->setBot($bot ?? null)
            ->allSlugList();
    }

    public function index(Request $request): BotMenuSlugCollection
    {
        $bot = Bot::query()
            ->find($request->botId ?? $request->bot_id ?? null);

        $slugs = BusinessLogic::slugs();

        if (!is_null($bot))
            $slugs = $slugs->setBot($bot);

        return $slugs
            ->list(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),
                $request->needGlobal ?? $request->isGlobal ?? null,
                $request->needDeleted ?? $request->isDeleted ?? false
            );
    }

    public function globalList(Request $request): BotMenuSlugCollection
    {
        $logic = BusinessLogic::slugs();

        if ($request->botUser->is_manager && !$request->botUser->is_admin)
            $logic = $logic->setBotUser($request->botUser);

        return $logic
            ->globalList(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function restore(Request $request, $slugId): BotMenuSlugResource
    {
        return BusinessLogic::slugs()
            ->restore($slugId);
    }

    public function destroy(Request $request, $slugId): BotMenuSlugResource
    {
        return BusinessLogic::slugs()
            ->destroy($slugId);
    }

    public function duplicate(Request $request, $slugId): BotMenuSlugResource
    {
        return BusinessLogic::slugs()
            ->duplicate($slugId);
    }


    /**
     * @throws ValidationException
     */
    public function createSlug(Request $request): BotMenuSlugResource
    {
        $request->validate([
            "command" => "required",
            "slug" => "required",
        ]);

        return BusinessLogic::slugs()
            ->create($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function updateSlug(Request $request): BotMenuSlugResource
    {

        $request->validate([
            "id" => "required",
        ]);

        return BusinessLogic::slugs()
            ->update($request->all());
    }
}
