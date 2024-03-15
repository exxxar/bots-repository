<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InlineQuerySlugStoreRequest;
use App\Http\Requests\InlineQuerySlugUpdateRequest;
use App\Http\Resources\InlineQuerySlugCollection;
use App\Http\Resources\InlineQuerySlugResource;
use App\Models\InlineQuerySlug;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InlineQuerySlugController extends Controller
{
    public function index(Request $request): Response
    {
        $inlineQuerySlugs = InlineQuerySlug::all();

        return new InlineQuerySlugCollection($inlineQuerySlugs);
    }

    public function store(InlineQuerySlugStoreRequest $request): Response
    {
        $inlineQuerySlug = InlineQuerySlug::create($request->validated());

        return new InlineQuerySlugResource($inlineQuerySlug);
    }

    public function show(Request $request, InlineQuerySlug $inlineQuerySlug): Response
    {
        return new InlineQuerySlugResource($inlineQuerySlug);
    }

    public function update(InlineQuerySlugUpdateRequest $request, InlineQuerySlug $inlineQuerySlug): Response
    {
        $inlineQuerySlug->update($request->validated());

        return new InlineQuerySlugResource($inlineQuerySlug);
    }

    public function destroy(Request $request, InlineQuerySlug $inlineQuerySlug): Response
    {
        $inlineQuerySlug->delete();

        return response()->noContent();
    }
}
