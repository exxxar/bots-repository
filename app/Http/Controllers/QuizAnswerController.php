<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizAnswerStoreRequest;
use App\Http\Requests\QuizAnswerUpdateRequest;
use App\Http\Resources\QuizAnswerCollection;
use App\Http\Resources\QuizAnswerResource;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuizAnswerController extends Controller
{
    public function index(Request $request): Response
    {
        $quizAnswers = QuizAnswer::all();

        return new QuizAnswerCollection($quizAnswers);
    }

    public function store(QuizAnswerStoreRequest $request): Response
    {
        $quizAnswer = QuizAnswer::create($request->validated());

        return new QuizAnswerResource($quizAnswer);
    }

    public function show(Request $request, QuizAnswer $quizAnswer): Response
    {
        return new QuizAnswerResource($quizAnswer);
    }

    public function update(QuizAnswerUpdateRequest $request, QuizAnswer $quizAnswer): Response
    {
        $quizAnswer->update($request->validated());

        return new QuizAnswerResource($quizAnswer);
    }

    public function destroy(Request $request, QuizAnswer $quizAnswer): Response
    {
        $quizAnswer->delete();

        return response()->noContent();
    }
}
