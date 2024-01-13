<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizAnswerStoreRequest extends FormRequest
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
            'quiz_question_id' => ['required', 'integer', 'exists:quiz_questions,id'],
            'text' => ['nullable', 'string'],
            'media_content' => ['nullable', 'json'],
            'content_type' => ['required', 'integer'],
            'is_right_answer' => ['required'],
            'points' => ['required', 'numeric'],
        ];
    }
}
