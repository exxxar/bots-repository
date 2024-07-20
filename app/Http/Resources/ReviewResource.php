<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'images' => $this->images,
            'rating' => $this->rating,
            'bot_user_id' => $this->bot_user_id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product' => $this->product ?? null,
            'bot_id' => $this->bot_id,
            'send_review_at' => $this->send_review_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
