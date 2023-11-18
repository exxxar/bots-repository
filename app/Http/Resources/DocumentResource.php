<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'file_id' => $this->file_id,
            'type' => $this->type,
            'params' => $this->params,
            'bot_id' => $this->bot_id,
            'bot_user_id' => $this->bot_user_id,
            'verified_at' => $this->verified_at,
        ];
    }
}
