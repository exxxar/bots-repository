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


    public function startQuiz(Request $request)
    {
        $request->validate([
            "quiz_id" => "required",
        ]);

        $quiz = Quiz::query()->find($request->quiz_id);

        if (is_null($quiz))
            return response()->noContent(404);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = $quiz->try_count ?? 1;

        $action = ActionStatus::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0,
                    'bot_user_id' => $botUser->id,
                    "data"=>[
                        "start_at"=>Carbon::now()->format('Y-m-d H:i:s')
                    ]
                ]);

        if (is_null($action->data)) {
            $action->current_attempts = 0;
            $action->save();
        }


        return response()->json([
            "action" => new ActionStatusResource($action),

        ]);
    }

    public function completeQuiz(Request $request)
    {

        $request->validate([
            "quiz_id" => "required",
        ]);

        $quiz = Quiz::query()->find($request->quiz_id);

        if (is_null($quiz))
            return response()->noContent(404);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = $quiz->try_count ?? 1;

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            $bot->main_channel ??
            env("BASE_ADMIN_CHANNEL");


        $action = ActionStatus::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_user_id' => $botUser->id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();

        $action->max_attempts = $maxAttempts;
        $action->save();

        $command = QuizCommand::query()->create([
            'title'=>$botUser->fio_from_telegram ?? $botUser->telegram_chat_id ?? '-',
            'description'=>null,
            'captain_id'=>$botUser->id,
            'creator_id'=>$botUser->id,
        ]);

        $points = 0;
        $data = $action->data ?? [];

        if (!empty($data["questions"] ?? []))
            foreach ($data["questions"] as $q)
                $points += $q["points"] ?? 0;

        $time = $data["start_at"]??null;

        $time = is_null($time)?Carbon::now():Carbon::parse($time);

        $time = Carbon::now()->sub($time);

        QuizResult::query()
            ->create([
                'quiz_id'=>$quiz->id,
                'quiz_command_id'=>$command->id,
                'points'=>$points,
                'time'=>$time->second,
                'result'=>$data["questions"] ?? [],
            ]);

        return response()->json([
            "action" => new ActionStatusResource($action),
        ]);

    }

    public function loadSingleQuiz(Request $request)
    {
        $request->validate([
            "quiz_id" => "required",
        ]);


        $quiz = Quiz::query()->find($request->quiz_id);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $action = ActionStatus::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        $points = 0;
        $data = $action->data ?? [];

        if (!empty($data["questions"] ?? []))
            foreach ($data["questions"] as $q)
                $points += $q["points"] ?? 0;


        $quiz->personal_info = (object)[
            "completed_at" => $action->completed_at ?? null,
            "max_attempts" => $action->max_attempts ?? 1,
            "current_attempts" => $action->current_attempts ?? 0,
            "result_points" => $points
        ];

        return new QuizResource($quiz);
    }

    public function checkAnswers(Request $request)
    {
        $request->validate([
            "quiz_question_id" => "required",
            "quiz_id" => "required",
            "answers" => "required"
        ]);


        $question = QuizQuestion::query()
            ->with(["answers"])
            ->where("id", $request->quiz_question_id)
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

        $quiz = Quiz::query()->find($request->quiz_id);

        if (is_null($quiz))
            return response()->noContent(404);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = $quiz->try_count ?? 1;

        $action = ActionStatus::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_user_id' => $botUser->id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        $tmp = $action->data ?? [];
        $tmpQuestionsResult = $tmp["questions"] ?? [];
        $tmpQuestionsResult[] = (object)[
            "question_id" => $question->id,
            "points" => $points,
            "is_right" => $hasRightAnswer,
        ];

        $action->data = (object)[
            "questions" => $tmpQuestionsResult,
        ];

        $action->save();

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
                "content" => $hasRightAnswer ? $question->success_media_content : $question->failure_media_content,
                "type" => $hasRightAnswer ? $question->success_media_content_type : $question->failure_media_content_type,
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
