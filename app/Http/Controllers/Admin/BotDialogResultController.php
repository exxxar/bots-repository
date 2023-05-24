<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotDialogResultStoreRequest;
use App\Http\Requests\BotDialogResultUpdateRequest;
use App\Http\Resources\BotDialogResultCollection;
use App\Http\Resources\BotDialogResultResource;
use App\Models\BotDialogResult;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotDialogResultController extends Controller
{
    public function index(Request $request): Response
    {
        $botDialogResults = BotDialogResult::all();

        return new BotDialogResultCollection($botDialogResults);
    }

    public function store(BotDialogResultStoreRequest $request): Response
    {
        $botDialogResult = BotDialogResult::create($request->validated());

        return new BotDialogResultResource($botDialogResult);
    }

    public function show(Request $request, BotDialogResult $botDialogResult): Response
    {
        return new BotDialogResultResource($botDialogResult);
    }

    public function update(BotDialogResultUpdateRequest $request, BotDialogResult $botDialogResult): Response
    {
        $botDialogResult->update($request->validated());

        return new BotDialogResultResource($botDialogResult);
    }

    public function destroy(Request $request, BotDialogResult $botDialogResult): Response
    {
        $botDialogResult->delete();

        return response()->noContent();
    }
}
