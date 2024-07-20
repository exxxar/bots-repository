<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'deliveryman_id' => $this->deliveryman_id,
            'delivery_service_info' => $this->delivery_service_info,
            'deliveryman_info' => $this->deliveryman_info,
            'product_details' => $this->product_details,
            'product_count' => $this->product_count,
            'summary_price' => $this->summary_price,
            'delivery_price' => $this->delivery_price,
            'delivery_range' => $this->delivery_range,
            'deliveryman_latitude' => $this->deliveryman_latitude,
            'deliveryman_longitude' => $this->deliveryman_longitude,
            'delivery_note' => $this->delivery_note,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            "service_rating" => $this->service_rating,
            "service_review" => $this->service_review,
            "address" => $this->address,
            "receiver_latitude" => $this->receiver_latitude,
            "receiver_longitude" => $this->receiver_longitude,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'order_type' => $this->order_type,
            'payed_at' => $this->payed_at,
            'review' => $this->review ?? null,
            'created_at' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s"),
            'is_cashback_crediting' => $this->is_cashback_crediting ?? true,
        ];
    }
}
