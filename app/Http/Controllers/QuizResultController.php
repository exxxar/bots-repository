<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizResultStoreRequest;
use App\Http\Requests\QuizResultUpdateRequest;
use App\Http\Resources\QuizResultCollection;
use App\Http\Resources\QuizResultResource;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuizResultController extends Controller
{
    public function index(Request $request): Response
    {
        $quizResults = QuizResult::all();

        return new QuizResultCollection($quizResults);
    }

    public function store(QuizResultStoreRequest $request): Response
    {
        $quizResult = QuizResult::create($request->validated());

        return new QuizResultResource($quizResult);
    }

    public function show(Request $request, QuizResult $quizResult): Response
    {
        return new QuizResultResource($quizResult);
    }

    public function update(QuizResultUpdateRequest $request, QuizResult $quizResult): Response
    {
        $quizResult->update($request->validated());

        return new QuizResultResource($quizResult);
    }

    public function destroy(Request $request, QuizResult $quizResult): Response
    {
        $quizResult->delete();

        return response()->noContent();
    }
}
