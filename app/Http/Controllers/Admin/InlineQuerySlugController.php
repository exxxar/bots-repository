<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\InlineQuerySlugStoreRequest;
use App\Http\Requests\InlineQuerySlugUpdateRequest;
use App\Http\Resources\InlineQuerySlugCollection;
use App\Http\Resources\InlineQuerySlugResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\Bot;
use App\Models\InlineQuerySlug;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class InlineQuerySlugController extends Controller
{
    public function listOfInlineQueries(Request $request): InlineQuerySlugCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::inlineQuery()
            ->setBot($bot ?? null)
            ->list(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    /**
     * @throws ValidationException
     */
    public function queryStore(Request $request): InlineQuerySlugResource
    {

        $request->validate([
            "bot_id" => "required",
            'command' => "required",
            'description' => "required",
        ]);


        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::inlineQuery()
            ->setBot($bot ?? null)
            ->store($request->all());
    }

    public function removeQuery(Request $request, $queryId): QuizResource
    {
        return BusinessLogic::inlineQuery()
            ->remove($queryId);
    }

}
