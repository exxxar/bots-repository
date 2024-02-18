<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\QuizResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\Quiz;
use App\Models\QuizCommand;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Carbon\Carbon;
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
                "key" => "quiz_id",
                "value" => null,

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


    /**
     * @throws ValidationException
     */
    public function startQuiz(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "quiz_id" => "required",
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $action = BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->setSlug($slug ?? null)
            ->startQuiz($request->all());

        return response()->json([
            "action" => $action,

        ]);
    }

    /**
     * @throws ValidationException
     */
    public function completeQuiz(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            "quiz_id" => "required",
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $action = BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->setSlug($slug ?? null)
            ->completeQuiz($request->all());

        return response()->json([
            "action" => $action,
        ]);

    }

    /**
     * @throws ValidationException
     */
    public function loadSingleQuiz(Request $request): QuizResource
    {
        $request->validate([
            "quiz_id" => "required",
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        return BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->setSlug($slug ?? null)
            ->loadSingleQuiz($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function checkAnswers(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "quiz_question_id" => "required",
            "quiz_id" => "required",
            "answers" => "required"
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $result = BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->setSlug($slug ?? null)
            ->checkAnswers($request->all());

        return response()->json($result);


    }

    /**
     * @throws ValidationException
     */
    public function checkAllAnswers(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "quiz_id" => "required",
            "answers" => "required"
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $result = BusinessLogic::quiz()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->setSlug($slug ?? null)
            ->checkAllAnswers($request->all());

        return response()->json($result);


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
                $request->order ?? "round",
                $request->direction ?? "asc"
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


    public function startQuizForm(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $quizId = (Collection::make($config[1])
            ->where("key", "quiz_id")
            ->first())["value"] ?? null;


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
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#" . (is_null($quizId) ?  "/quizzes" : "/quiz/$quizId")
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
