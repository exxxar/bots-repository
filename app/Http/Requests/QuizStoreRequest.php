<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizStoreRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed_at' => ['nullable'],
            'start_at' => ['nullable'],
            'end_at' => ['nullable'],
            'display_type' => ['required', 'integer'],
            'time_limit' => ['required', 'numeric'],
            'show_answers' => ['required'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
        ];
    }
}
