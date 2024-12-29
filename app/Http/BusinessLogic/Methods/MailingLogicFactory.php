<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\QueueCollection;
use App\Http\Resources\QueueResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\Queue;
use App\Models\Quiz;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MailingLogicFactory extends BaseLogicFactory
{
    use LogicUtilities;


    /**
     * @throws HttpException
     */
    public function list($search = null, $size = null, $order = null, $direction = null): QueueCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $queues = Queue::query()
            // ->withTrashed()
            ->where("bot_id", $this->bot->id)
            ->whereNull("sent_at");

        if (!is_null($search))
            $queues = $queues->where("content", 'like', "%$search%");

        $queues = $queues
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);


        return new QueueCollection($queues);
    }

    /**
     * @throws HttpException
     */
    public function remove($queueId, $force = false): QueueResource
    {
        $queue =  Queue::query()->where("id", $queueId)
                ->first() ;

        if (is_null($queue))
            throw new HttpException(404, "Рассылка не найдена");

        $tmp = $queue;

        if ($force) {
            $queue->forceDelete();
            return new QueueResource($tmp);
        }

        $queue->delete();

        return new QueueResource($tmp);
    }

}
