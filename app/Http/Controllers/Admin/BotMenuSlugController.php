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
    public function index(Request $request): BotMenuSlugCollection
    {
        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->botId ?? null)
            ->first();

        return BusinessLogic::slugs()
            ->setBot($bot)
            ->list(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),
                $request->needGlobal ?? $request->isGlobal ?? false
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
