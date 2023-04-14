<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotUserStoreRequest;
use App\Http\Requests\BotUserUpdateRequest;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotUserController extends Controller
{
    public function index(Request $request): Response
    {
        $botUsers = BotUser::all();

        return new BotUserCollection($botUsers);
    }

    public function store(BotUserStoreRequest $request): Response
    {
        $botUser = BotUser::create($request->validated());

        return new BotUserResource($botUser);
    }

    public function show(Request $request, BotUser $botUser): Response
    {
        return new BotUserResource($botUser);
    }

    public function update(BotUserUpdateRequest $request, BotUser $botUser): Response
    {
        $botUser->update($request->validated());

        return new BotUserResource($botUser);
    }

    public function destroy(Request $request, BotUser $botUser): Response
    {
        $botUser->delete();

        return response()->noContent();
    }
}
