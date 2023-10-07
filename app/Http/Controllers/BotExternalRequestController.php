<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotExternalRequestStoreRequest;
use App\Http\Requests\BotExternalRequestUpdateRequest;
use App\Http\Resources\BotExternalRequestCollection;
use App\Http\Resources\BotExternalRequestResource;
use App\Models\BotExternalRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotExternalRequestController extends Controller
{
    public function index(Request $request): Response
    {
        $botExternalRequests = BotExternalRequest::all();

        return new BotExternalRequestCollection($botExternalRequests);
    }

    public function store(BotExternalRequestStoreRequest $request): Response
    {
        $botExternalRequest = BotExternalRequest::create($request->validated());

        return new BotExternalRequestResource($botExternalRequest);
    }

    public function show(Request $request, BotExternalRequest $botExternalRequest): Response
    {
        return new BotExternalRequestResource($botExternalRequest);
    }

    public function update(BotExternalRequestUpdateRequest $request, BotExternalRequest $botExternalRequest): Response
    {
        $botExternalRequest->update($request->validated());

        return new BotExternalRequestResource($botExternalRequest);
    }

    public function destroy(Request $request, BotExternalRequest $botExternalRequest): Response
    {
        $botExternalRequest->delete();

        return response()->noContent();
    }
}
