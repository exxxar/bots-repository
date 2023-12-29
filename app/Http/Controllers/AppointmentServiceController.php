<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentServiceStoreRequest;
use App\Http\Requests\AppointmentServiceUpdateRequest;
use App\Http\Resources\AppointmentServiceCollection;
use App\Http\Resources\AppointmentServiceResource;
use App\Models\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentServiceController extends Controller
{
    public function index(Request $request): Response
    {
        $appointmentServices = AppointmentService::all();

        return new AppointmentServiceCollection($appointmentServices);
    }

    public function store(AppointmentServiceStoreRequest $request): Response
    {
        $appointmentService = AppointmentService::create($request->validated());

        return new AppointmentServiceResource($appointmentService);
    }

    public function show(Request $request, AppointmentService $appointmentService): Response
    {
        return new AppointmentServiceResource($appointmentService);
    }

    public function update(AppointmentServiceUpdateRequest $request, AppointmentService $appointmentService): Response
    {
        $appointmentService->update($request->validated());

        return new AppointmentServiceResource($appointmentService);
    }

    public function destroy(Request $request, AppointmentService $appointmentService): Response
    {
        $appointmentService->delete();

        return response()->noContent();
    }
}
