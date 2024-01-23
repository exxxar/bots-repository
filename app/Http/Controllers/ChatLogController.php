<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatLogStoreRequest;
use App\Http\Requests\ChatLogUpdateRequest;
use App\Http\Resources\ChatLogCollection;
use App\Http\Resources\ChatLogResource;
use App\Models\ChatLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChatLogController extends Controller
{
    public function index(Request $request): Response
    {
        $chatLogs = ChatLog::all();

        return new ChatLogCollection($chatLogs);
    }

    public function store(ChatLogStoreRequest $request): Response
    {
        $chatLog = ChatLog::create($request->validated());

        return new ChatLogResource($chatLog);
    }

    public function show(Request $request, ChatLog $chatLog): Response
    {
        return new ChatLogResource($chatLog);
    }

    public function update(ChatLogUpdateRequest $request, ChatLog $chatLog): Response
    {
        $chatLog->update($request->validated());

        return new ChatLogResource($chatLog);
    }

    public function destroy(Request $request, ChatLog $chatLog): Response
    {
        $chatLog->delete();

        return response()->noContent();
    }
}
