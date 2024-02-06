<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotPageStoreRequest;
use App\Http\Requests\BotPageUpdateRequest;
use App\Http\Resources\BotPageCollection;
use App\Http\Resources\BotPageResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BotPageController extends Controller
{
    /**
     * @throws \HttpException
     */
    public function index(Request $request): BotPageCollection
    {
        return BusinessLogic::pages()
            ->setBot(Bot::query()->find($request->botId ?? $request->bot_id ?? null))
            ->list(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),
                $request->needDeleted ?? $request->need_deleted ?? false,
                $request->needNewFirst ?? $request->need_new_first ?? false
            );

    }

    /**
     * @throws \Exception
     */
    public function duplicate(Request $request, $pageId): BotPageResource
    {
        return BusinessLogic::pages()
            ->duplicate($pageId);
    }

    /**
     * @throws \HttpException
     */
    public function destroy($pageId): BotPageResource
    {
        return BusinessLogic::pages()
            ->destroy($pageId);
    }

    /**
     * @throws \HttpException
     */
    public function forceDestroy($pageId): BotPageResource
    {
        return BusinessLogic::pages()
            ->destroy($pageId, true);
    }

    /**
     * @throws \HttpException
     */
    public function restorePage($pageId): BotPageResource
    {
        return BusinessLogic::pages()
            ->restore($pageId);
    }

    /**
     * @throws ValidationException
     * @throws \HttpException
     */
    public function createPage(Request $request): BotPageResource
    {
        $request->validate([
            //  "content" => "required",
            "command" => "required",
            "comment" => "required",
            "bot_id" => "required",
        ]);

        return BusinessLogic::pages()
            ->setBot(Bot::query()->find($request->bot_id ?? null))
            ->create($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);
    }

    /**
     * @throws ValidationException
     * @throws \HttpException
     */
    public function updatePage(Request $request): BotPageResource
    {
        $request->validate([
            "id" => "required",
            // "content" => "required",
            "command" => "required",
            "comment" => "required",
            "bot_id" => "required",
            "slug_id" => "required",
        ]);

        return BusinessLogic::pages()
            ->setBot(Bot::query()->find($request->bot_id ?? null))
            ->update($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);
    }
}
