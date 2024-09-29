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
            'url' => $this->url ?? null,
            'is_active' => $this->is_active ?? true,
            'config' => $this->config ?? [],
        ];
    }
}
