<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionStatusCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenLoaded("slug")->id ?? null,
            'title' => $this->whenLoaded("slug")->command ?? null,
        ];
    }
}
