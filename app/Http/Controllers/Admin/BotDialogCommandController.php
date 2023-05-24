<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotDialogCommandStoreRequest;
use App\Http\Requests\BotDialogCommandUpdateRequest;
use App\Http\Resources\BotDialogCommandCollection;
use App\Http\Resources\BotDialogCommandResource;
use App\Models\BotDialogCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotDialogCommandController extends Controller
{
    public function index(Request $request): Response
    {
        $botDialogCommands = BotDialogCommand::all();

        return new BotDialogCommandCollection($botDialogCommands);
    }

    public function store(BotDialogCommandStoreRequest $request): Response
    {
        $botDialogCommand = BotDialogCommand::create($request->validated());

        return new BotDialogCommandResource($botDialogCommand);
    }

    public function show(Request $request, BotDialogCommand $botDialogCommand): Response
    {
        return new BotDialogCommandResource($botDialogCommand);
    }

    public function update(BotDialogCommandUpdateRequest $request, BotDialogCommand $botDialogCommand): Response
    {
        $botDialogCommand->update($request->validated());

        return new BotDialogCommandResource($botDialogCommand);
    }

    public function destroy(Request $request, BotDialogCommand $botDialogCommand): Response
    {
        $botDialogCommand->delete();

        return response()->noContent();
    }
}
