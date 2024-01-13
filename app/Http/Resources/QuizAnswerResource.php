<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quiz_question_id' => $this->quiz_question_id,
            'text' => $this->text,
            'media_content' => $this->media_content,
            'content_type' => $this->content_type,
            'is_right_answer' => $this->is_right_answer,
            'points' => $this->points,
            'quizQuestion' => QuizQuestionResource::make($this->whenLoaded('quizQuestion')),
        ];
    }
}
