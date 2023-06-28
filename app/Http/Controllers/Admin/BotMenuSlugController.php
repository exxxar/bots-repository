<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotMenuSlugStoreRequest;
use App\Http\Requests\BotMenuSlugUpdateRequest;
use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Models\BotMenuSlug;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotMenuSlugController extends Controller
{
    public function index(Request $request): Response
    {
        $botMenuSlugs = BotMenuSlug::all();

        return new BotMenuSlugCollection($botMenuSlugs);
    }

    public function store(BotMenuSlugStoreRequest $request): Response
    {
        $botMenuSlug = BotMenuSlug::create($request->validated());

        return new BotMenuSlugResource($botMenuSlug);
    }

    public function show(Request $request, BotMenuSlug $botMenuSlug): Response
    {
        return new BotMenuSlugResource($botMenuSlug);
    }

    public function update(BotMenuSlugUpdateRequest $request, BotMenuSlug $botMenuSlug): Response
    {
        $botMenuSlug->update($request->validated());

        return new BotMenuSlugResource($botMenuSlug);
    }

    public function destroy(Request $request, $slugId): Response
    {

        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            return response()->noContent();

        $botMenuSlug->deleted_at = Carbon::now();
        $botMenuSlug->save();

        return response()->noContent();
    }

    public function duplicate(Request $request, $slugId){
        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            return response()->noContent();

        $botMenuSlug = $botMenuSlug->replicate();
        $botMenuSlug->save();

        return response()->noContent();
    }


    public function createSlug(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "command" => "required",
            "slug" => "required",
        ]);

        BotMenuSlug::query()->create($request->all());

        return response()->noContent();
    }

    public function updateSlug(Request $request)
    {

        $request->validate([
            "id" => "required",
            "bot_id" => "required",
        ]);

        $slug = BotMenuSlug::query()->find($request->id);
        if (is_null($slug))
            return response()->noContent(404);

        $tmp = (object)$request->all();
        $tmp->is_global = $tmp->is_global ?? false;
        $tmp->config = json_decode($tmp->config);

        $slug->update((array)$tmp);

        return response()->noContent();
    }
}
