<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CdekResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'account' => $this->account,
            'secure_password' => $this->secure_password,
            'is_active' => $this->is_active,
            'config' => $this->config,
            'bot' => BotResource::make($this->whenLoaded('bot')),
        ];
    }
}
