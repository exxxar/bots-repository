<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuizAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_question_id',
        'text',
        'media_content',
        'content_type',
        'is_right_answer',
        'points',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quiz_question_id' => 'integer',
        'is_right_answer' => 'boolean',
        'points' => 'double',
    ];

    public function question(): HasOne
    {
        return $this->hasOne(QuizQuestion::class);
    }


}
