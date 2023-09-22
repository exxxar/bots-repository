<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmoCrmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'auth_code' => $this->auth_code,
            'redirect_uri' => $this->redirect_uri,
            'subdomain' => $this->subdomain,
            'fields' => $this->fields ?? null,
        ];
    }
}
