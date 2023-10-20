<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotWarningStoreRequest;
use App\Http\Requests\BotWarningUpdateRequest;
use App\Http\Resources\BotWarningCollection;
use App\Http\Resources\BotWarningResource;
use App\Models\BotWarning;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotWarningController extends Controller
{
    public function index(Request $request): Response
    {
        $botWarnings = BotWarning::all();

        return new BotWarningCollection($botWarnings);
    }

    public function store(BotWarningStoreRequest $request): Response
    {
        $botWarning = BotWarning::create($request->validated());

        return new BotWarningResource($botWarning);
    }

    public function show(Request $request, BotWarning $botWarning): Response
    {
        return new BotWarningResource($botWarning);
    }

    public function update(BotWarningUpdateRequest $request, BotWarning $botWarning): Response
    {
        $botWarning->update($request->validated());

        return new BotWarningResource($botWarning);
    }

    public function destroy(Request $request, BotWarning $botWarning): Response
    {
        $botWarning->delete();

        return response()->noContent();
    }
}
