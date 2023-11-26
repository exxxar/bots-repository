<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotCustomFieldSettingStoreRequest;
use App\Http\Requests\BotCustomFieldSettingUpdateRequest;
use App\Http\Resources\BotCustomFieldSettingCollection;
use App\Http\Resources\BotCustomFieldSettingResource;
use App\Models\BotCustomFieldSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotCustomFieldSettingController extends Controller
{
    public function index(Request $request): Response
    {
        $botCustomFieldSettings = BotCustomFieldSetting::all();

        return new BotCustomFieldSettingCollection($botCustomFieldSettings);
    }

    public function store(BotCustomFieldSettingStoreRequest $request): Response
    {
        $botCustomFieldSetting = BotCustomFieldSetting::create($request->validated());

        return new BotCustomFieldSettingResource($botCustomFieldSetting);
    }

    public function show(Request $request, BotCustomFieldSetting $botCustomFieldSetting): Response
    {
        return new BotCustomFieldSettingResource($botCustomFieldSetting);
    }

    public function update(BotCustomFieldSettingUpdateRequest $request, BotCustomFieldSetting $botCustomFieldSetting): Response
    {
        $botCustomFieldSetting->update($request->validated());

        return new BotCustomFieldSettingResource($botCustomFieldSetting);
    }

    public function destroy(Request $request, BotCustomFieldSetting $botCustomFieldSetting): Response
    {
        $botCustomFieldSetting->delete();

        return response()->noContent();
    }
}
