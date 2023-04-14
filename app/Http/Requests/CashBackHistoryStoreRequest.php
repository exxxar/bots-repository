<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashBackHistoryStoreRequest extends FormRequest
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
            'money_in_check' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'operation_type' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
