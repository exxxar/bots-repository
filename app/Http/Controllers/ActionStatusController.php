<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionStatusStoreRequest;
use App\Http\Requests\ActionStatusUpdateRequest;
use App\Http\Resources\ActionStatusCollection;
use App\Http\Resources\ActionStatusResource;
use App\Models\ActionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActionStatusController extends Controller
{
    public function index(Request $request): Response
    {
        $actionStatuses = ActionStatus::all();

        return new ActionStatusCollection($actionStatuses);
    }

    public function store(ActionStatusStoreRequest $request): Response
    {
        $actionStatus = ActionStatus::create($request->validated());

        return new ActionStatusResource($actionStatus);
    }

    public function show(Request $request, ActionStatus $actionStatus): Response
    {
        return new ActionStatusResource($actionStatus);
    }

    public function update(ActionStatusUpdateRequest $request, ActionStatus $actionStatus): Response
    {
        $actionStatus->update($request->validated());

        return new ActionStatusResource($actionStatus);
    }

    public function destroy(Request $request, ActionStatus $actionStatus): Response
    {
        $actionStatus->delete();

        return response()->noContent();
    }
}
