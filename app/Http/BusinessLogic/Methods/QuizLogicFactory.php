<?php

namespace App\Http\BusinessLogic\Methods;

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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuizLogicFactory
{
    protected $bot;

    public function __construct()
    {
        $this->bot = null;

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
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC');


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
            'start_at' => "required",
            'end_at' => "required",
            'time_limit' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $success = json_decode($data['success_message'] ?? '[]');
        $failure = json_decode($data['failure_message'] ?? '[]');


        $result = Quiz::query()->updateOrCreate(
            [
                'bot_id' => $this->bot->id,
            ],
            [
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
                "is_active" => $data["is_active"] ?? true,
                "success_percent" => $data["success_percent"] ?? 50,
                "success_message" => $success,
                "failure_message" => $failure,

            ]);

        return new QuizResource($result);
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
