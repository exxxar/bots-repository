<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'user_id' => $this->user_id,

            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'age' => $this->age,
            'city' => $this->city,
            'country' => $this->country,
            'address' => $this->address,
            'sex' => $this->sex,

            'fio_from_telegram' => $this->fio_from_telegram,
            'telegram_chat_id' => $this->telegram_chat_id,

            'parent_id' => $this->parent_id,
            'is_vip' => $this->is_vip,
            'is_admin' => $this->is_admin,
            'is_work' => $this->is_work,
            'user' => $this->whenLoaded("user") ?? null,
            'user_in_location' => $this->user_in_location,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
