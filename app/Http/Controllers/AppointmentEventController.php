<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentEventStoreRequest;
use App\Http\Requests\AppointmentEventUpdateRequest;
use App\Http\Resources\AppointmentEventCollection;
use App\Http\Resources\AppointmentEventResource;
use App\Models\AppointmentEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentEventController extends Controller
{
    public function index(Request $request): Response
    {
        $appointmentEvents = AppointmentEvent::all();

        return new AppointmentEventCollection($appointmentEvents);
    }

    public function store(AppointmentEventStoreRequest $request): Response
    {
        $appointmentEvent = AppointmentEvent::create($request->validated());

        return new AppointmentEventResource($appointmentEvent);
    }

    public function show(Request $request, AppointmentEvent $appointmentEvent): Response
    {
        return new AppointmentEventResource($appointmentEvent);
    }

    public function update(AppointmentEventUpdateRequest $request, AppointmentEvent $appointmentEvent): Response
    {
        $appointmentEvent->update($request->validated());

        return new AppointmentEventResource($appointmentEvent);
    }

    public function destroy(Request $request, AppointmentEvent $appointmentEvent): Response
    {
        $appointmentEvent->delete();

        return response()->noContent();
    }
}
