<?php

namespace App\Http\Controllers\Admin;


use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuizStoreRequest;
use App\Http\Requests\QuizUpdateRequest;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\Bot;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class QuizController extends Controller
{


    public function listOfQuizCommands(Request $request, $quizId): \App\Http\Resources\QuizCommandCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->listOfQuizCommands(
                $quizId,
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }


    public function listOfQuizQuestions(Request $request, $quizId): \App\Http\Resources\QuizQuestionCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->listOfQuizQuestions(
                $quizId,
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }
    public function listOfQuiz(Request $request): QuizCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->listOfQuiz(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    public function listOfResults(Request $request)
    {

    }



    /**
     * @throws ValidationException
     */
    public function quizCommandStore(Request $request): \App\Http\Resources\QuizCommandResource
    {
        $request->validate([
            "bot_id" => "required",
            "quiz_id" => "required",
            'title' => "required",
            'description' => "required",
        ]);


        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->quizCommandStore($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function quizQuestionStore(Request $request): \App\Http\Resources\QuizQuestionResource
    {
        $request->validate([
            "bot_id" => "required",
            "quiz_id" => "required",
            'text' => "required",
            'round' => "required",
        ]);


        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->quizQuestionStore($request->all());
    }
    /**
     * @throws ValidationException
     */
    public function quizStore(Request $request): QuizResource
    {

        $request->validate([
            "bot_id" => "required",
            'title' => "required",
            'description' => "required",
            'start_at' => "required",
            'end_at' => "required",
            'time_limit' => "required",
        ]);


        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->quizStore($request->all());
    }

    public function removeQuizCommand(Request $request, $quizCommandId): \App\Http\Resources\QuizCommandResource
    {
        return BusinessLogic::quiz()
            ->removeQuizCommand($quizCommandId);
    }

    public function removeQuizAnswer(Request $request, $quizAnswerId): \App\Http\Resources\QuizAnswerResource
    {
        return BusinessLogic::quiz()
            ->removeQuizAnswer($quizAnswerId);
    }

    public function removeQuestionQuiz(Request $request, $quizQuestionId): \App\Http\Resources\QuizQuestionResource
    {
        return BusinessLogic::quiz()
            ->removeQuizQuestion($quizQuestionId);
    }
    public function removeQuiz(Request $request, $quizId): QuizResource
    {
        return BusinessLogic::quiz()
            ->removeQuiz($quizId);
    }

    public function restoreQuiz(Request $request, $quizId)
    {

    }

}
