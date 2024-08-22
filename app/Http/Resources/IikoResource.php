<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IikoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'bot_id' => $this->bot_id?? null,
            'api_login' => $this->api_login?? null,
            'organization_id' => $this->organization_id?? null,
            'terminal_group_id' => $this->terminal_group_id?? null,
        ];
    }
}
