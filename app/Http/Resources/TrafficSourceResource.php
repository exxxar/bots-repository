<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrafficSourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'bot_user_id' => $this->bot_user_id,
            'comment' => $this->comment,
            'source' => $this->source,
            'botUser' => BotUserResource::make($this->whenLoaded('botUser')),
        ];
    }
}
