<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrontPadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'hook_url' => $this->hook_url,
            'channel' => $this->channel,
            'affiliate' => $this->affiliate,
            'point' => $this->point,
            'token' => $this->token,
            'pays' => $this->pays,
            'is_active' => $this->is_active ?? false,
            'statuses' => $this->statuses,
        ];
    }
}
