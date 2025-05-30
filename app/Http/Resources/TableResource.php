<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'creator_id' => $this->creator_id,
            'officiant_id' => $this->officiant_id,
            'number' => $this->number,
            'closed_at' => $this->closed_at,
            'start_at' => $this->created_at,
            'config' => $this->config,
            'additional_services' => $this->additional_services,
            'bot_user_id' => $this->bot_user_id,
            'clients' => BotUserResource::collection($this->whenLoaded('clients')),
            'officiant' => BotUserResource::make($this->whenLoaded('officiant')),
            'creator' => BotUserResource::make($this->whenLoaded('creator')),
        ];
    }
}
