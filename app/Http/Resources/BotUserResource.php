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
            'username' => $this->username ?? null,
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

            'parent_id' => $this->parent_id ?? null,
            'is_vip' => $this->is_vip ?? false,
            'is_admin' => $this->is_admin ?? false,
            'is_work' => $this->is_work ?? false,
            'in_dialog_mode' => $this->in_dialog_mode ?? false,
            'cashBack' => $this->cashBack ?? null,

            'is_deliveryman' => $this->is_deliveryman ?? false,
            'current_latitude' => $this->current_latitude ?? 0,
            'current_longitude' => $this->current_longitude ?? 0,

            'user' => $this->whenLoaded("user") ?? null,
            'user_in_location' => $this->user_in_location,
            'temporary' => $this->temporary ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
