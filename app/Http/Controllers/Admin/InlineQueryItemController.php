<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InlineQueryItemStoreRequest;
use App\Http\Requests\InlineQueryItemUpdateRequest;
use App\Http\Resources\InlineQueryItemCollection;
use App\Http\Resources\InlineQueryItemResource;
use App\Models\InlineQueryItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InlineQueryItemController extends Controller
{
    public function index(Request $request): Response
    {
        $inlineQueryItems = InlineQueryItem::all();

        return new InlineQueryItemCollection($inlineQueryItems);
    }

    public function store(InlineQueryItemStoreRequest $request): Response
    {
        $inlineQueryItem = InlineQueryItem::create($request->validated());

        return new InlineQueryItemResource($inlineQueryItem);
    }

    public function show(Request $request, InlineQueryItem $inlineQueryItem): Response
    {
        return new InlineQueryItemResource($inlineQueryItem);
    }

    public function update(InlineQueryItemUpdateRequest $request, InlineQueryItem $inlineQueryItem): Response
    {
        $inlineQueryItem->update($request->validated());

        return new InlineQueryItemResource($inlineQueryItem);
    }

    public function destroy(Request $request, InlineQueryItem $inlineQueryItem): Response
    {
        $inlineQueryItem->delete();

        return response()->noContent();
    }
}
