<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_sender_id' => $this->user_sender_id,
            'user_recipient_id' => $this->user_recipient_id,
            'bot_id' => $this->bot_id,
            'activated' => $this->activated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'bot' => BotResource::make($this->whenLoaded('bot')),
        ];
    }
}
