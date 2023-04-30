<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotMenuSlugStoreRequest;
use App\Http\Requests\BotMenuSlugUpdateRequest;
use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Models\BotMenuSlug;
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

    public function destroy(Request $request, BotMenuSlug $botMenuSlug): Response
    {
        $botMenuSlug->delete();

        return response()->noContent();
    }
}
