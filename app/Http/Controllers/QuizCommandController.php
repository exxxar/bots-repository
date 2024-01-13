<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizCommandStoreRequest;
use App\Http\Requests\QuizCommandUpdateRequest;
use App\Http\Resources\QuizCommandCollection;
use App\Http\Resources\QuizCommandResource;
use App\Models\QuizCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuizCommandController extends Controller
{
    public function index(Request $request): Response
    {
        $quizCommands = QuizCommand::all();

        return new QuizCommandCollection($quizCommands);
    }

    public function store(QuizCommandStoreRequest $request): Response
    {
        $quizCommand = QuizCommand::create($request->validated());

        return new QuizCommandResource($quizCommand);
    }

    public function show(Request $request, QuizCommand $quizCommand): Response
    {
        return new QuizCommandResource($quizCommand);
    }

    public function update(QuizCommandUpdateRequest $request, QuizCommand $quizCommand): Response
    {
        $quizCommand->update($request->validated());

        return new QuizCommandResource($quizCommand);
    }

    public function destroy(Request $request, QuizCommand $quizCommand): Response
    {
        $quizCommand->delete();

        return response()->noContent();
    }
}
