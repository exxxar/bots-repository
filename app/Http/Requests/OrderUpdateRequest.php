<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'delivery_service_info' => ['nullable', 'json'],
            'deliveryman_info' => ['nullable', 'json'],
            'product_details' => ['nullable', 'json'],
            'product_count' => ['required', 'integer'],
            'summary_price' => ['required', 'numeric'],
            'delivery_price' => ['required', 'numeric'],
            'delivery_range' => ['required', 'numeric'],
            'deliveryman_latitude' => ['required', 'numeric'],
            'deliveryman_longitude' => ['required', 'numeric'],
            'delivery_note' => ['nullable', 'string'],
            'receiver_name' => ['nullable', 'string', 'max:255'],
            'receiver_phone' => ['nullable', 'string', 'max:255'],
            'receiver_get_id' => ['required', 'integer', 'exists:receiver_gets,id'],
            'status' => ['required', 'integer'],
            'order_type' => ['required', 'integer'],
            'payed_at' => ['nullable'],
        ];
    }
}
