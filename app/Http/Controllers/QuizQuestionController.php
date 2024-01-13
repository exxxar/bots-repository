<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizQuestionStoreRequest;
use App\Http\Requests\QuizQuestionUpdateRequest;
use App\Http\Resources\QuizQuestionCollection;
use App\Http\Resources\QuizQuestionResource;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuizQuestionController extends Controller
{
    public function index(Request $request): Response
    {
        $quizQuestions = QuizQuestion::all();

        return new QuizQuestionCollection($quizQuestions);
    }

    public function store(QuizQuestionStoreRequest $request): Response
    {
        $quizQuestion = QuizQuestion::create($request->validated());

        return new QuizQuestionResource($quizQuestion);
    }

    public function show(Request $request, QuizQuestion $quizQuestion): Response
    {
        return new QuizQuestionResource($quizQuestion);
    }

    public function update(QuizQuestionUpdateRequest $request, QuizQuestion $quizQuestion): Response
    {
        $quizQuestion->update($request->validated());

        return new QuizQuestionResource($quizQuestion);
    }

    public function destroy(Request $request, QuizQuestion $quizQuestion): Response
    {
        $quizQuestion->delete();

        return response()->noContent();
    }
}
