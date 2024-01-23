<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class QuizScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_start_quiz",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Начать квиз",
                'comment' => "Старт выбранного квиза",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Доступные в системе квизы",

            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Открыть",
            ],
            [
                "type" => "image",
                "key" => "image_main",
                "value" => null,

            ],

        ];
        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }


    public function checkAnswers(Request $request)
    {
        $request->validate([
            "quizQuestionId" => "required",
            "answers" => "required"
        ]);

        $question = QuizQuestion::query()
            ->with(["answers"])
            ->where("id", $request->quizQuestionId)
            ->first();

        if (is_null($question))
            return response()->noContent(404);

        if (is_null($question->answers ?? null))
            return response()->noContent(403);

        $answerPool = json_decode($request->answers ?? '[]');

        $points = 0;
        $hasRightAnswer = false;
        foreach ($question->answers as $answer) {
            if (is_null($answer))
                continue;

            if (in_array($question->is_open ? $answer->text : $answer->id, is_array($answerPool) ? $answerPool : [$answerPool])) {


                $points += $answer->points;

                if ($answer->is_right_answer)
                    $hasRightAnswer = true;

                if (!$question->is_multiply)
                    break;
            }

        }

        /*  QuizResult::query()->create([
              'quiz_id',
              'quiz_command_id',
              'points',
              'time',
              'result',
          ]);*/
        return response()->json([
            "question" => (object)[
                "id" => $question->id,
                "text" => $question->text,
                "message" => $hasRightAnswer ? $question->success_message : $question->failure_message,
                "content" => "DQACAgIAAxkBAAIjpWV5uwGwbULL5-fsrTEhth9QUlLsAAIWOwACuxyASz_Q4sIZLZqXMwQ",//$hasRightAnswer? $question->success_media_content : $question->failure_media_content,
                "type" => "video_note",//$hasRightAnswer? $question->success_media_content_type : $question->failure_media_content_type,
            ],
            "points" => $points,
            "is_right" => $hasRightAnswer,
        ]);


    }

    public function listOfQuizQuestions(Request $request, $quizId): \App\Http\Resources\QuizQuestionCollection
    {
        $bot = $request->bot;

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

    public function listOfQuizCommands(Request $request, $quizId): \App\Http\Resources\QuizCommandCollection
    {

        $bot = $request->bot;

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

    public function loadQuizResultList(Request $request)
    {

        $bot = $request->bot ?? null;

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->listOfQuiz(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    public function loadQuizList(Request $request)
    {

        $bot = $request->bot ?? null;

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->listOfQuiz(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    /**
     * @throws ValidationException
     */
    public function quizCommandStore(Request $request): \App\Http\Resources\QuizCommandResource
    {
        $request->validate([
            "quiz_id" => "required",
            'title' => "required",
            'description' => "required",
        ]);


        $bot = $request->bot;

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->quizCommandStore($request->all());
    }


    public function startQuiz(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];


        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Квиз";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Открыть";

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $img = (Collection::make($config[1])
            ->where("key", "image_main")
            ->first())["value"] ?? null;


        $keyboard = [
            [
                ["text" => $btnText, "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/quiz"
                ]],
            ],

        ];

        if (!is_null($img))
            \App\Facades\BotManager::bot()
                ->replyPhoto($mainText,
                    $img,
                    $keyboard
                );
        else
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($mainText,
                    $keyboard);

    }
}
