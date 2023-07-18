<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bot_id' => $this->bot_id,
            'payload' => $this->payload,
            'currency' => $this->currency,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'order_info' => $this->order_info,
            'products_info' => $this->products_info,
            'shipping_address' => $this->shipping_address,
            'telegram_payment_charge_id' => $this->telegram_payment_charge_id,
            'provider_payment_charge_id' => $this->provider_payment_charge_id,
            'completed_at' => $this->completed_at,
        ];
    }
}
