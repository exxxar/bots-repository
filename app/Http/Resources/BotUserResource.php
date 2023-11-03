<?php

namespace App\Http\Resources;

use App\Models\CashBack;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        if (is_null($this->cashBack)) {
            $this->cashBack = CashBack::query()
                ->where("user_id", "$this->user_id")
                ->where("bot_id", "$this->bot_id")
                ->first();

            if (!is_null($this->cashBack)) {
                $this->cashBack->bot_user_id = $this->id;
                $this->cashBack->save();
            } else {
                $this->cashBack = CashBack::query()
                    ->create([
                        'user_id' => $this->user_id,
                        'bot_id' => $this->bot_id,
                        'bot_user_id' => $this->id,
                        'amount' => 0,
                    ]);
            }

        }


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
            'is_manager' => $this->is_manager ?? false,
            'in_dialog_mode' => $this->in_dialog_mode ?? false,
            'cashBack' => $this->cashBack ?? null,

            'is_deliveryman' => $this->is_deliveryman ?? false,
            'current_latitude' => $this->current_latitude ?? 0,
            'current_longitude' => $this->current_longitude ?? 0,

            'user' => $this->whenLoaded("user") ?? null,
            'manager' => $this->whenLoaded("manager") ?? null,
            'user_in_location' => $this->user_in_location,
            'temporary' => $this->temporary ?? null,

            'blocked_at' => $this->blocked_at ?? null,
            'blocked_message' => $this->blocked_message ?? null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,


        ];
    }
}
