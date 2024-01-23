<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'text' => $this->text,
            'media_content' => $this->media_content,
            'content_type' => $this->content_type,
            'is_multiply' => $this->is_multiply,
            'is_open' => $this->is_open,
            'round' => $this->round,
            "success_message"=> $this->success_message,
            "failure_message"=> $this->failure_message,
            "success_media_content"=> $this->success_media_content ,
            "failure_media_content"=> $this->failure_media_content ,
            "success_media_content_type"=> $this->success_media_content_type,
            "failure_media_content_type"=> $this->failure_media_content_type ,

            'quizzes' => QuizCollection::make($this->whenLoaded('quizzes')),
            'answers' => QuizAnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
