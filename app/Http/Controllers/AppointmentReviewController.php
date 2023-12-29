<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentReviewStoreRequest;
use App\Http\Requests\AppointmentReviewUpdateRequest;
use App\Http\Resources\AppointmentReviewCollection;
use App\Http\Resources\AppointmentReviewResource;
use App\Models\AppointmentReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentReviewController extends Controller
{
    public function index(Request $request): Response
    {
        $appointmentReviews = AppointmentReview::all();

        return new AppointmentReviewCollection($appointmentReviews);
    }

    public function store(AppointmentReviewStoreRequest $request): Response
    {
        $appointmentReview = AppointmentReview::create($request->validated());

        return new AppointmentReviewResource($appointmentReview);
    }

    public function show(Request $request, AppointmentReview $appointmentReview): Response
    {
        return new AppointmentReviewResource($appointmentReview);
    }

    public function update(AppointmentReviewUpdateRequest $request, AppointmentReview $appointmentReview): Response
    {
        $appointmentReview->update($request->validated());

        return new AppointmentReviewResource($appointmentReview);
    }

    public function destroy(Request $request, AppointmentReview $appointmentReview): Response
    {
        $appointmentReview->delete();

        return response()->noContent();
    }
}
