<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationUpdateRequest extends FormRequest
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
            'images' => ['nullable', 'json'],
            'lat' => ['required', 'numeric'],
            'lon' => ['required', 'numeric'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location_channel' => ['nullable', 'string', 'max:255'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'is_active' => ['required'],
            'can_booking' => ['required'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
