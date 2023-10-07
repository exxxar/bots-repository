<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotPageController extends Controller
{
    /**
     * @throws \HttpException
     */
    public function index(Request $request): BotPageCollection
    {
        return BusinessLogic::pages()
            ->setBot($request->bot ?? null)
            ->list(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
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
