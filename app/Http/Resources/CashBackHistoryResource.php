<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashBackHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'money_in_check' => $this->money_in_check,
            'description' => $this->description,
            'operation_type' => $this->operation_type,
            'user_id' => $this->user_id,
            'bot_id' => $this->bot_id,
            'employee_id' => $this->employee_id,
            'employee' => $this->employee ?? null,
            'amount' => $this->amount,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
