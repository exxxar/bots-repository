<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashBackSubStoreRequest extends FormRequest
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
            'cash_back_id' => ['required', 'integer', 'exists:cash_backs,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
