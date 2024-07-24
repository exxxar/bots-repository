<?php

namespace App\Http\Controllers\Bots\Web;


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

class QueueController extends Controller
{

    public function list(Request $request): \App\Http\Resources\QueueCollection
    {

        $bot = $request->bot;

        return BusinessLogic::mailing()
            ->setBot($bot ?? null)
            ->list(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }


    /**
     * @throws ValidationException
     */
    public function store(Request $request): \App\Http\Resources\QueueResource
    {
        $request->validate([
            "message" => "required",
            "cron_time" => "required",
        ]);

        $bot = $request->bot ?? null;

       return BusinessLogic::bots()
            ->setBot($bot)
            ->sendToQueue($request->all());

    }

    public function remove(Request $request, $queueId): \App\Http\Resources\QueueResource
    {
        $bot = $request->bot;

        return BusinessLogic::mailing()
            ->setBot($bot ?? null)
            ->remove($queueId);
    }


}
