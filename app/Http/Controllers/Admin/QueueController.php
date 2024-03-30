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

class QueueController extends Controller
{

    public function list(Request $request): \App\Http\Resources\QueueCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::mailing()
            ->setBot($bot ?? null)
            ->list(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }


    public function store(Request $request)
    {

    }

    public function remove(Request $request, $queueId): \App\Http\Resources\QueueResource
    {
        return BusinessLogic::mailing()
            ->remove($queueId);
    }



}
