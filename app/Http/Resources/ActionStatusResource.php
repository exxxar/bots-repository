<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bot_id' => $this->bot_id,
            'script' => $this->script,
            'max_attempts' => $this->max_attempts,
            'current_attempts' => $this->current_attempts,
            'completed_at' => $this->completed_at,
            'data' => $this->data,
        ];
    }
}
