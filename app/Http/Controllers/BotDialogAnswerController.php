<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotDialogAnswerStoreRequest;
use App\Http\Requests\BotDialogAnswerUpdateRequest;
use App\Http\Resources\BotDialogAnswerCollection;
use App\Http\Resources\BotDialogAnswerResource;
use App\Models\BotDialogAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotDialogAnswerController extends Controller
{
    public function index(Request $request): Response
    {
        $botDialogAnswers = BotDialogAnswer::all();

        return new BotDialogAnswerCollection($botDialogAnswers);
    }

    public function store(BotDialogAnswerStoreRequest $request): Response
    {
        $botDialogAnswer = BotDialogAnswer::create($request->validated());

        return new BotDialogAnswerResource($botDialogAnswer);
    }

    public function show(Request $request, BotDialogAnswer $botDialogAnswer): Response
    {
        return new BotDialogAnswerResource($botDialogAnswer);
    }

    public function update(BotDialogAnswerUpdateRequest $request, BotDialogAnswer $botDialogAnswer): Response
    {
        $botDialogAnswer->update($request->validated());

        return new BotDialogAnswerResource($botDialogAnswer);
    }

    public function destroy(Request $request, BotDialogAnswer $botDialogAnswer): Response
    {
        $botDialogAnswer->delete();

        return response()->noContent();
    }
}
