<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quiz_id' => $this->quiz_id,
            'quiz_command_id' => $this->quiz_command_id,
            'points' => $this->points,
            'time' => $this->time,
            'result' => $this->result,
        ];
    }
}
