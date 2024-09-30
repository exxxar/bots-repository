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

    public function loadChains(Request $request)
    {
        $request->validate([
            "start_page_id" => "required",
        ]);

        $pages = [];


        $page = BotPage::query()
            ->find($request->start_page_id);

        if (is_null($page))
            return response()->noContent(404);

        $ids = [];
        while (true) {
            $page = BotPage::query()
                ->find($page->next_page_id);

            if (is_null($page))
                break;

            $pages[] = $page;

            if (in_array($page->id, $ids))
                break;

            $ids[] = $page->id;

            if (is_null($page->next_page_id))
                break;
        }

        return new BotPageCollection($pages);

    }

    public function updateChains(Request $request)
    {

        $request->validate([
            "start_page_id" => "required",
            "links" => "required"
        ]);


        $links = [$request->start_page_id, ...($request->links ?? [])];


        for ($index = 0; $index < count($links) - 1; $index++) {
            $page = BotPage::query()
                ->find($links[$index]);

            if (is_null($page))
                continue;

            $page->next_page_id = $links[$index + 1];
            $page->save();
        }

        return \response()->noContent();

    }

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
     */
    public function addPagesByKeyboard(Request $request)
    {
        $request->validate([
            "keyboard" => "required",
            "settings" => "required",
        ]);

        BusinessLogic::pages()
            ->setBot(Bot::query()->find($request->bot_id ?? null))
            ->addPagesByKeyboard($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function addPages(Request $request)
    {
        $request->validate([
            "pages" => "required|array",
        ]);

        BusinessLogic::pages()
            ->setBot(Bot::query()->find($request->bot_id ?? null))
            ->addPages($request->all());
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
