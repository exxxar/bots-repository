<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,

            'avatar_url' => $this->avatar_url,

            'role_id' => $this->role_id,
            'blocked_at' => $this->blocked_at,
            'blocked_message' => $this->blocked_message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'role' => RoleResource::make($this->whenLoaded('role')),
            //'transactions' => TransactionCollection::make($this->whenLoaded('transactions')),
        ];
    }
}
