<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotTypeStoreRequest;
use App\Http\Requests\BotTypeUpdateRequest;
use App\Http\Resources\BotTypeCollection;
use App\Http\Resources\BotTypeResource;
use App\Models\BotType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotTypeController extends Controller
{
    public function index(Request $request): Response
    {
        $botTypes = BotType::all();

        return new BotTypeCollection($botTypes);
    }

    public function store(BotTypeStoreRequest $request): Response
    {
        $botType = BotType::create($request->validated());

        return new BotTypeResource($botType);
    }

    public function show(Request $request, BotType $botType): Response
    {
        return new BotTypeResource($botType);
    }

    public function update(BotTypeUpdateRequest $request, BotType $botType): Response
    {
        $botType->update($request->validated());

        return new BotTypeResource($botType);
    }

    public function destroy(Request $request, BotType $botType): Response
    {
        $botType->delete();

        return response()->noContent();
    }
}
