<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotMethods;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\AppointmentEventCollection;
use App\Http\Resources\AppointmentReviewResource;
use App\Http\Resources\QuizAnswerResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizCommandCollection;
use App\Http\Resources\QuizCommandResource;
use App\Http\Resources\QuizQuestionCollection;
use App\Http\Resources\QuizQuestionResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResultCollection;
use App\Models\ActionStatus;
use App\Models\AmoCrm;
use App\Models\AppointmentEvent;
use App\Models\AppointmentReview;
use App\Models\AppointmentService;
use App\Models\Bot;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizCommand;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuizLogicFactory
{
    protected $bot;
    protected $botUser;
    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;


    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }


    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }


    /**
     * @throws HttpException
     */
    public function listOfQuizRounds($quizId): array
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");


        $quiz = Quiz::query()
            ->with(["questions"])
            ->find($quizId);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден!");


        $questions = $quiz->questions
            ->unique("round")
            ->pluck("round");


        return $questions->toArray();
    }


    /**
     * @throws HttpException
     */
    public function listOfQuizCommands($quizId, $search = null, $size = null, $order = null, $direction = null): QuizCommandCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $quiz = Quiz::query()
            ->with(["questions"])
            ->find($quizId);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден!");


        $commands = QuizCommand::query()
            // ->withTrashed()
            ->whereIn("id", array_values($quiz->commands->pluck("id")->toArray()));

        if (!is_null($search))
            $commands = $commands->where(function ($q) use ($search) {
                $q->where("title", 'like', "%$search%")
                    ->orWhere("description", 'like', "%$search%");
            });

        $commands = $commands
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new QuizCommandCollection($commands);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function startQuiz(array $data): ActionStatusResource
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            'quiz_id' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

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
                    "data" => [
                        "start_at" => Carbon::now()->format('Y-m-d H:i:s')
                    ]
                ]);


        /*    if (!is_null($action->data["questions"] ?? null)) {
                $action->current_attempts = 0;
                $action->data = [
                    "start_at" => Carbon::now()->format('Y-m-d H:i:s')
                ];

                $action->save();
            }*/


        return new ActionStatusResource($action);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     * @throws \Exception
     */
    public function completeQuiz(array $data): ActionStatusResource
    {

        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            'quiz_id' => "required",
        ]);


        if ($validator->fails())
            throw new ValidationException($validator);


        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

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

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();

        $action->max_attempts = $maxAttempts;
        $action->save();

        $command = QuizCommand::query()->create([
            'title' => $botUser->fio_from_telegram ?? $botUser->telegram_chat_id ?? '-',
            'description' => null,
            'captain_id' => $botUser->id,
            'creator_id' => $botUser->id,
        ]);

        $command->players()->sync([$botUser->id]);
        $quiz->commands()->sync([$command->id]);

        $points = 0;
        $data = $action->data ?? [];


        $maxResult = 0;

        foreach ($quiz->questions as $question)
            $maxResult += $question->max_points ?? 0;

        if (!empty($data["questions"] ?? []))
            foreach ($data["questions"] as $q)
                $points += $q["points"] ?? 0;

        $time = $data["start_at"] ?? null;

        $time = is_null($time) ? Carbon::now() : Carbon::parse($time);

        $time = Carbon::now()->sub($time);

        QuizResult::query()
            ->create([
                'quiz_id' => $quiz->id,
                'quiz_command_id' => $command->id,
                'points' => $points,
                'time' => $time->second,
                'result' => $data["questions"] ?? [],
            ]);


        $thread = $bot->topics["actions"] ?? null;
        $nameUser = BotMethods::prepareUserName($botUser);
        $message = "$nameUser, спасибо за участие в нашем Квизе \"" . ($quiz->title ?? 'Без названия') . "\"! Вы прошли все задания за  <strong>$time</strong> сек и набрали <strong>$points</strong> баллов!";

        $tmpWin = "Ура, Вы выиграли";
        $tmpLoose = "Увы, Вы проиграли";
        $message .= $points >= (($maxResult * ($quiz->success_percent ?? 50)) / 100) ?
            $quiz->success_message[random_int(0, count($quiz->success_message ?? [$tmpWin]) - 1)] ?? $tmpWin :
            $quiz->failure_message[random_int(0, count($quiz->failure_message ?? [$tmpLoose]) - 1)] ?? $tmpLoose;


        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $botUser->telegram_chat_id,
                $message,
            )
            ->sendMessage(
                $bot->order_channel ?? $bot->main_channel ?? null,
                "$nameUser прошел квиз #$quiz->id (" . ($quiz->title ?? 'Без названия') . ") за  <strong>$time</strong> сек и набрал <strong>$points</strong> баллов из <strong>$maxResult</strong> ",
                $thread
            );

        return new ActionStatusResource($action);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function loadSingleQuiz(array $data): QuizResource
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            'quiz_id' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;


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

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function checkAnswers(array $data): object
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            "quiz_question_id" => "required",
            "quiz_id" => "required",
            "answers" => "required"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $question = QuizQuestion::query()
            ->with(["answers"])
            ->where("id", $data["quiz_question_id"])
            ->first();

        if (is_null($question))
            throw new HttpException(404, "Вопрос не найден");

        if (is_null($question->answers ?? null))
            throw new HttpException(403, "Ошибка в ответах..");

        $answerPool = json_decode($data["answers"] ?? '[]');

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

        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

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


        Log::info(($hasRightAnswer?"true":"false")." ".print_r($hasRightAnswer ? $question->success_message : $question->failure_message, true));

        return (object)[
            "question" => (object)[
                "id" => $question->id,
                "text" => $question->text,
                "message" => $hasRightAnswer ? $question->success_message : $question->failure_message,
                "content" => $hasRightAnswer ? $question->success_media_content : $question->failure_media_content,
                "type" => $hasRightAnswer ? $question->success_media_content_type : $question->failure_media_content_type,
            ],
            "points" => $points,
            "is_right" => $hasRightAnswer,
        ];


    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function checkAllAnswers(array $data): array
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            "quiz_id" => "required",
            "answers" => "required"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $answers = json_decode($data["answers"]);

        $ids = Collection::make($answers)
            ->pluck("id");

        $questions = QuizQuestion::query()
            ->with(["answers"])
            ->whereHas("answers")
            ->whereIn("id", $ids)
            ->get();


        if (count($questions)==0)
            throw new HttpException(404, "Вопросы не найден");

        $bot = $this->bot;
        $botUser = $this->botUser;
        $slug = $this->slug;

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


        $result = [];

        foreach ($questions as $question) {
            $points = 0;
            $hasRightAnswer = false;
            foreach ($question->answers as $answer) {
                if (is_null($answer))
                    continue;


                $answerPool = array_filter($answers, function ($q) use ($question){
                   return $q->id ==  $question->id;
                });

                $answerPool = Collection::make($answerPool)
                    ->pluck("value");

                //dd($answerPool);

                if (in_array($question->is_open ?
                    $answer->text : $answer->id,
                    is_array($answerPool) ? $answerPool : [$answerPool[0]])) {


                    $points += $answer->points;

                    if ($answer->is_right_answer)
                        $hasRightAnswer = true;

                    if (!$question->is_multiply)
                        break;
                }

            }


            $tmpObject = [
                "question_id" => $question->id,
                "points" => $points,
                "is_right" => $hasRightAnswer,
            ];

            $tmpQuestionsResult[] = (object)$tmpObject;

            $result[] = [
               ...$tmpObject,
                "question" => (object)[
                    "id" => $question->id,
                    "text" => $question->text,
                    "message" => $hasRightAnswer ? $question->success_message : $question->failure_message,
                    "content" => $hasRightAnswer ? $question->success_media_content : $question->failure_media_content,
                    "type" => $hasRightAnswer ? $question->success_media_content_type : $question->failure_media_content_type,
                ],
            ];

        }

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


        return $result;


    }

    /**
     * @throws HttpException
     */
    public function listOfQuizQuestions($quizId, $search = null, $size = null, $order = null, $direction = null): QuizQuestionCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $quiz = Quiz::query()
            ->with(["questions"])
            ->find($quizId);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден!");


        $questions = QuizQuestion::query()
            // ->withTrashed()
            ->whereIn("id", array_values($quiz->questions->pluck("id")->toArray()));

        if (!is_null($search))
            $questions = $questions->where("text", 'like', "%$search%");

        $questions = $questions
            ->orderBy($order ?? 'updated_at', $direction ?? 'desc');

        if ($size == -1)
            $questions = $questions->get();
        else
            $questions = $questions->paginate($size);

        return new QuizQuestionCollection($questions);
    }

    /**
     * @throws HttpException
     */
    public function listOfQuiz($search = null, $size = null, $order = null, $direction = null): QuizCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $events = Quiz::query()
            // ->withTrashed()
            ->where("bot_id", $this->bot->id);

        if (!is_null($search))
            $events = $events->where(function ($q) use ($search) {
                $q->where("title", 'like', "%$search%")
                    ->orWhere("description", 'like', "%$search%");
            });


        $events = $events
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new QuizCollection($events);
    }

    public function listOfResults($quizId, $size = null, $order = null, $direction = null): QuizResultCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $quiz = Quiz::query()
            ->find($quizId);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден!");


        $results = QuizResult::query()
            ->with(["command"])
            // ->withTrashed()
            ->where("quiz_id", $quizId)
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new QuizResultCollection($results);
    }


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function quizCommandStore(array $data): QuizCommandResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'quiz_id' => "required",
            'title' => "required",
            'description' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        if (is_null($data["id"] ?? null))
            $command = QuizCommand::query()
                ->create([
                    'title' => $data["title"] ?? null,
                    'description' => $data["description"] ?? null,
                    'creator_id' => $data["creator_id"] ?? null,
                    'captain_id' => $data["captain_id"] ?? null,
                ]);
        else {
            $command = QuizCommand::query()->find($data["id"]);
            $command->update([
                'title' => $data["title"] ?? null,
                'description' => $data["description"] ?? null,
                'creator_id' => $data["creator_id"] ?? null,
                'captain_id' => $data["captain_id"] ?? null,
            ]);
        }

        if (is_null($data["id"] ?? null))
            $quiz->commands()->attach($command->id);

        if (isset($data["players"])) {
            $players = json_decode($data["players"] ?? '[]');

            $ids = [];
            foreach ($players as $player) {
                $ids[] = $player->id;
            }

            $command->players()->sync($ids);
        }

        $command = $command->refresh();

        return new QuizCommandResource($command);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function quizQuestionStore(array $data): QuizQuestionResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'quiz_id' => "required",
            'text' => "required",
            'round' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $quiz = Quiz::query()->find($data["quiz_id"]);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $tmp = [
            'text' => $data["text"] ?? null,
            'round' => $data["round"] ?? null,
            'media_content' => $data["media_content"] ?? null,
            'content_type' => $data["content_type"] ?? null,
            'is_multiply' => ($data["is_multiply"] ?? false) == "true",
            'is_open' => ($data["is_open"] ?? false) == "true",
            'success_message' => $data["success_message"] ?? null,
            'failure_message' => $data["failure_message"] ?? null,
            'success_media_content' => $data["success_media_content"] ?? null,
            'failure_media_content' => $data["failure_media_content"] ?? null,
            'success_media_content_type' => $data["success_media_content_type"] ?? null,
            'failure_media_content_type' => $data["failure_media_content_type"] ?? null,

        ];


        if (!is_null($data["id"])) {
            $question = QuizQuestion::query()
                ->where("id", $data["id"])
                ->first();

            $question->update($tmp);
        } else
            $question = QuizQuestion::query()->create($tmp);

        if (is_null($data["id"] ?? null))
            $quiz->questions()->attach($question->id);

        if (isset($data["answers"])) {
            $answers = json_decode($data["answers"] ?? '[]');

            foreach ($answers as $answer) {
                $answer = (object)$answer;
                $answer->quiz_question_id = $question->id;

                if (!is_null($answer->id ?? null)) {
                    $result = QuizAnswer::query()->find($answer->id);
                    $result->update((array)$answer);
                    // dd($answer);
                } else
                    QuizAnswer::query()
                        ->create((array)$answer);
            }
        }

        $question = $question->refresh();

        return new QuizQuestionResource($question);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function quizStore(array $data): QuizResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            'title' => "required",
            'description' => "required",
            'time_limit' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $success = json_decode($data['success_message'] ?? '[]');
        $failure = json_decode($data['failure_message'] ?? '[]');


        $tmp = [
            'bot_id' => $this->bot->id,
            'title' => $data["title"] ?? null,
            'image' => $data["image"] ?? null,
            'description' => $data["description"] ?? null,
            'completed_at' => is_null($data["completed_at"] ?? null) ? null : Carbon::parse($data["completed_at"]),
            'start_at' => is_null($data["start_at"] ?? null) ? null : Carbon::parse($data["start_at"]),
            'end_at' => is_null($data["end_at"] ?? null) ? null : Carbon::parse($data["end_at"]),
            'display_type' => $data["display_type"] ?? 0,
            'time_limit' => $data["time_limit"] ?? 30,
            'show_answers' => ($data["show_answers"] ?? false) == "true",
            "polling_mode" => ($data["polling_mode"] ?? false) == "true",
            "round_mode" => ($data["round_mode"] ?? false) == "true",
            "try_count" => $data["try_count"] ?? 1,
            "is_active" => ($data["is_active"] ?? false) == "true",
            "success_percent" => $data["success_percent"] ?? 50,
            "success_message" => $success,
            "failure_message" => $failure,

        ];

        if (is_null($data["id"] ?? null)) {
            $quiz = Quiz::query()->create($tmp);
        } else {
            $quiz = Quiz::query()->find($data["id"]);
            $quiz->update($tmp);
        }

        return new QuizResource($quiz);
    }


    /**
     * @throws HttpException
     */
    public function removeQuizCommand($quizCommandId, $force = false): QuizCommandResource
    {

        $quizCommand = !$force ?
            QuizCommand::query()
                ->where("id", $quizCommandId)
                ->first() :
            QuizCommand::query()
                ->withTrashed()
                ->where("id", $quizCommandId)
                ->first();

        if (is_null($quizCommand))
            throw new HttpException(404, "Команда квизе не найден");

        $tmp = $quizCommand;

        if ($force) {
            $quizCommand->forceDelete();
            return new QuizCommandResource($tmp);
        }

        $quizCommand->delete();

        return new QuizCommandResource($tmp);
    }


    /**
     * @throws HttpException
     */
    public function removeQuizAnswer($quizAnswerId, $force = false): QuizAnswerResource
    {

        $quizAnswer = !$force ?
            QuizAnswer::query()
                ->where("id", $quizAnswerId)
                ->first() :
            QuizAnswer::query()
                ->withTrashed()
                ->where("id", $quizAnswerId)
                ->first();

        if (is_null($quizAnswer))
            throw new HttpException(404, "Ответ на вопрос в квизе не найден");

        $tmp = $quizAnswer;

        if ($force) {
            $quizAnswer->forceDelete();
            return new QuizAnswerResource($tmp);
        }

        $quizAnswer->delete();

        return new QuizAnswerResource($tmp);
    }


    /**
     * @throws HttpException
     */
    public function removeQuizQuestion($quizQuestionId, $force = false): QuizQuestionResource
    {

        $quizQuestion = !$force ?
            QuizQuestion::query()
                ->with(["quizzes"])
                ->where("id", $quizQuestionId)
                ->first() :
            QuizQuestion::query()
                ->withTrashed()
                ->with(["quizzes"])
                ->where("id", $quizQuestionId)
                ->first();

        if (is_null($quizQuestion))
            throw new HttpException(404, "Вопрос в квизе не найден");

        $tmp = $quizQuestion;

        foreach ($quizQuestion->quizzes as $quiz)
            $quiz->questions()->detach($quizQuestion->id);

        if ($force) {
            $quizQuestion->forceDelete();
            return new QuizQuestionResource($tmp);
        }

        $quizQuestion->delete();

        return new QuizQuestionResource($tmp);
    }

    /**
     * @throws HttpException
     */
    public function removeQuiz($quizId, $force = false): QuizResource
    {
        $quiz = !$force ?
            Quiz::query()->where("id", $quizId)
                ->first() :
            Quiz::query()->withTrashed()->where("id", $quizId)
                ->first();

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден");

        $tmp = $quiz;

        if ($force) {
            $quiz->forceDelete();
            return new QuizResource($tmp);
        }

        $quiz->delete();

        return new QuizResource($tmp);
    }

    public function restoreQuiz()
    {

    }

}
