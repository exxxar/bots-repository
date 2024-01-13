<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizResultUpdateRequest extends FormRequest
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
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
            'quiz_command_id' => ['required', 'integer', 'exists:quiz_commands,id'],
            'points' => ['required', 'numeric'],
            'time' => ['required', 'numeric'],
            'result' => ['nullable', 'json'],
        ];
    }
}
