<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BitrixResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'host' => $this->host,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'scopes' => $this->scopes,
            'bot' => BotResource::make($this->whenLoaded('bot')),
        ];
    }
}
