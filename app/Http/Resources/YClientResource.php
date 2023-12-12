<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'login' => $this->login,
            'password' => $this->password,
            'partner_token' => $this->partner_token,
            'company' => $this->company ?? null,
            'fields' => $this->fields ?? null,

        ];
    }
}
