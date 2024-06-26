<?php

namespace App\Http\Resources;

use App\Models\BotMenuTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'description' => $this->description,
            'completed_at' => $this->completed_at,
            "polling_mode"=> $this->polling_mode,
            "round_mode"=> $this->round_mode,
            "try_count"=> $this->try_count,
            "is_active"=> $this->is_active,
            "success_percent"=> $this->success_percent,
            "success_message"=> $this->success_message,
            "failure_message"=> $this->failure_message,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'display_type' => $this->display_type,
            'time_limit' => $this->time_limit,
            'show_answers' => $this->show_answers,
            'personal_info' => $this->personal_info ?? null,
            'bot_id' => $this->bot_id,
            'success_inline_keyboard_id' => $this->success_inline_keyboard_id,
            'failure_inline_keyboard_id' => $this->failure_inline_keyboard_id,
            'questions' => QuizQuestionResource::collection($this->whenLoaded('questions')),
            'commands' => QuizCommandResource::collection($this->whenLoaded('commands')),
            'success_inline_keyboard' =>$this->whenLoaded('successReplyKeyboard'),
            'failure_inline_keyboard' => $this->whenLoaded('failureReplyKeyboard'),
        ];
    }
}
