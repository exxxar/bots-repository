<?php

namespace App\Http\Resources;

use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizCommandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'quizzes' => QuizCollection::make($this->whenLoaded('quizzes')),
            'players' => BotUserResource::collection($this->whenLoaded('players')),
        ];
    }
}
