<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotMenuTemplateStoreRequest;
use App\Http\Requests\BotMenuTemplateUpdateRequest;
use App\Http\Resources\BotMenuTemplateCollection;
use App\Http\Resources\BotMenuTemplateResource;
use App\Models\BotMenuTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotMenuTemplateController extends Controller
{
    public function index(Request $request): Response
    {
        $botMenuTemplates = BotMenuTemplate::all();

        return new BotMenuTemplateCollection($botMenuTemplates);
    }

    public function store(BotMenuTemplateStoreRequest $request): Response
    {
        $botMenuTemplate = BotMenuTemplate::create($request->validated());

        return new BotMenuTemplateResource($botMenuTemplate);
    }

    public function show(Request $request, BotMenuTemplate $botMenuTemplate): Response
    {
        return new BotMenuTemplateResource($botMenuTemplate);
    }

    public function update(BotMenuTemplateUpdateRequest $request, BotMenuTemplate $botMenuTemplate): Response
    {
        $botMenuTemplate->update($request->validated());

        return new BotMenuTemplateResource($botMenuTemplate);
    }

    public function destroy(Request $request, BotMenuTemplate $botMenuTemplate): Response
    {
        $botMenuTemplate->delete();

        return response()->noContent();
    }
}
