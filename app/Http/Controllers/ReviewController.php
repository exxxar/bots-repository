<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function index(Request $request): Response
    {
        $reviews = Review::all();

        return new ReviewCollection($reviews);
    }

    public function store(ReviewStoreRequest $request): Response
    {
        $review = Review::create($request->validated());

        return new ReviewResource($review);
    }

    public function show(Request $request, Review $review): Response
    {
        return new ReviewResource($review);
    }

    public function update(ReviewUpdateRequest $request, Review $review): Response
    {
        $review->update($request->validated());

        return new ReviewResource($review);
    }

    public function destroy(Request $request, Review $review): Response
    {
        $review->delete();

        return response()->noContent();
    }
}
