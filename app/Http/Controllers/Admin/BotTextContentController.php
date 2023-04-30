<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotTextContentStoreRequest;
use App\Http\Requests\BotTextContentUpdateRequest;
use App\Http\Resources\BotTextContentCollection;
use App\Http\Resources\BotTextContentResource;
use App\Models\BotTextContent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotTextContentController extends Controller
{
    public function index(Request $request): Response
    {
        $botTextContents = BotTextContent::all();

        return new BotTextContentCollection($botTextContents);
    }

    public function store(BotTextContentStoreRequest $request): Response
    {
        $botTextContent = BotTextContent::create($request->validated());

        return new BotTextContentResource($botTextContent);
    }

    public function show(Request $request, BotTextContent $botTextContent): Response
    {
        return new BotTextContentResource($botTextContent);
    }

    public function update(BotTextContentUpdateRequest $request, BotTextContent $botTextContent): Response
    {
        $botTextContent->update($request->validated());

        return new BotTextContentResource($botTextContent);
    }

    public function destroy(Request $request, BotTextContent $botTextContent): Response
    {
        $botTextContent->delete();

        return response()->noContent();
    }
}
