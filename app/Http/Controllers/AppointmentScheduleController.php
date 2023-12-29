<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentScheduleStoreRequest;
use App\Http\Requests\AppointmentScheduleUpdateRequest;
use App\Http\Resources\AppointmentScheduleCollection;
use App\Http\Resources\AppointmentScheduleResource;
use App\Models\AppointmentSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentScheduleController extends Controller
{
    public function index(Request $request): Response
    {
        $appointmentSchedules = AppointmentSchedule::all();

        return new AppointmentScheduleCollection($appointmentSchedules);
    }

    public function store(AppointmentScheduleStoreRequest $request): Response
    {
        $appointmentSchedule = AppointmentSchedule::create($request->validated());

        return new AppointmentScheduleResource($appointmentSchedule);
    }

    public function show(Request $request, AppointmentSchedule $appointmentSchedule): Response
    {
        return new AppointmentScheduleResource($appointmentSchedule);
    }

    public function update(AppointmentScheduleUpdateRequest $request, AppointmentSchedule $appointmentSchedule): Response
    {
        $appointmentSchedule->update($request->validated());

        return new AppointmentScheduleResource($appointmentSchedule);
    }

    public function destroy(Request $request, AppointmentSchedule $appointmentSchedule): Response
    {
        $appointmentSchedule->delete();

        return response()->noContent();
    }
}
