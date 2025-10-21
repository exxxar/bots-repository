<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id'=> $this->bot_id,
            'bot_partner_id'=> $this->bot_partner_id,
            'title'=> $this->title,
            'description'=> $this->description,
            'image'=> $this->image,
            'is_active'=> $this->is_active,
            'extra_charge'=> $this->extra_charge,
            'config'=> $this->config,
            'legal_info'=> $this->legal_info,
            'products' => $this->whenLoaded("products") ,
            ];
    }
}
