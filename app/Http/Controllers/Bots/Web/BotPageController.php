<?php

namespace App\Http\Controllers\Bots\Web;

use App\Classes\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotPageCollection;
use App\Http\Resources\BotPageResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotPageController extends Controller
{

    public function activatePagePassword(Request $request)
    {
        $request->validate([
            "page_id" => "required",
            "password" => "required"
        ]);

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;

        $page = BotPage::query()
            ->where("id", $request->page_id)
            ->first();

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $page->bot_menu_slug_id)
            ->first();

        if (is_null($action))
            return response()->noContent(404);

        $action->data = (object)[
            "activate_at" => Carbon::now()
        ];
        $action->save();

        \App\Facades\BotManager::bot()->runPage($request->page_id);

        return response()->noContent();
    }

    /**
     * @throws \HttpException
     */
    public function index(Request $request): BotPageCollection
    {
        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
            ->list(
                search: $request->search ?? null,
                size: $request->get("size") ?? config('app.results_per_page'),
                order: $request->order_by ?? "updated_at",
                direction: $request->direction ?? "asc"
            );

    }

    /**
     * @throws \Exception
     */
    public function duplicate(Request $request, $pageId): BotPageResource
    {
        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
            ->duplicate($pageId);
    }

    /**
     * @throws HttpException
     */
    public function destroy(Request $request, $pageId): BotPageResource
    {
        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
            ->destroy($pageId);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function createPage(Request $request): BotPageResource
    {
        $request->validate([
            // "content" => "required",
            "command" => "required",
            "comment" => "required",
        ]);

        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
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
            //"content" => "required",
            "command" => "required",
            "comment" => "required",
            "slug_id" => "required",
        ]);

        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
            ->update($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);
    }
}
