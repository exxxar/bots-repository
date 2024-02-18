<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'media_content',
        'content_type',
        'is_multiply',
        'is_open',
        'round',
        "success_message",
        "failure_message",
        "success_media_content",
        "failure_media_content",
        "success_media_content_type",
        "failure_media_content_type",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_multiply' => 'boolean',
        'is_open' => 'boolean',
    ];

    protected $with = ["answers"];

    protected $appends = ["max_points"];

    public function getMaxPointsAttribute(){
        if (is_null($this->answers()))
            return 0;

        $sum = 0;

        $answers = $this->answers()->get();
        foreach ($answers as $answer)
            $sum += ($answer->points ?? 0);

        return $sum;

    }
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }
}
