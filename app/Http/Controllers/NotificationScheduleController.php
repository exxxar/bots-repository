<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationScheduleStoreRequest;
use App\Http\Requests\NotificationScheduleUpdateRequest;
use App\Http\Resources\NotificationScheduleCollection;
use App\Http\Resources\NotificationScheduleResource;
use App\Models\NotificationSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationScheduleController extends Controller
{
    public function index(Request $request): Response
    {
        $notificationSchedules = NotificationSchedule::all();

        return new NotificationScheduleCollection($notificationSchedules);
    }

    public function store(NotificationScheduleStoreRequest $request): Response
    {
        $notificationSchedule = NotificationSchedule::create($request->validated());

        return new NotificationScheduleResource($notificationSchedule);
    }

    public function show(Request $request, NotificationSchedule $notificationSchedule): Response
    {
        return new NotificationScheduleResource($notificationSchedule);
    }

    public function update(NotificationScheduleUpdateRequest $request, NotificationSchedule $notificationSchedule): Response
    {
        $notificationSchedule->update($request->validated());

        return new NotificationScheduleResource($notificationSchedule);
    }

    public function destroy(Request $request, NotificationSchedule $notificationSchedule): Response
    {
        $notificationSchedule->delete();

        return response()->noContent();
    }
}
