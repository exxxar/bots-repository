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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BotMenuSlugController extends Controller
{
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
                $request->needGlobal ?? $request->isGlobal ?? null
            );
    }

    public function globalList(Request $request): BotMenuSlugCollection
    {

        return BusinessLogic::slugs()
            ->globalList(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
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
