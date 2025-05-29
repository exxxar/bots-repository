<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanySecurityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'address' => $this->address,
            'phones' => $this->phones,
            'links' => $this->links,
            'email' => $this->email,
            'schedule' => $this->schedule,
            'law_params' => $this->law_params ?? null,
            'manager' => $this->manager,
           // 'transactions' => TransactionCollection::make($this->whenLoaded('transactions')),
        ];
    }
}
