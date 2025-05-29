<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'bot_count' => $this->bot_count ?? 0,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'address' => $this->address,
            'phones' => $this->phones,
            'vat_code' => $this->vat_code ?? 1,
            'links' => $this->links,
            'email' => $this->email,
            'schedule' => $this->schedule,
            'law_params' => $this->law_params ?? null,
            'manager' => $this->manager,
            'is_active' => $this->is_active,
            'creator_id' => $this->creator_id ?? null,
            'owner_id' => $this->owner_id ?? null,
            'blocked_message' => $this->blocked_message,
            'blocked_at' => $this->blocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            // 'transactions' => TransactionCollection::make($this->whenLoaded('transactions')),
        ];
    }
}
