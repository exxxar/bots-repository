<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id',
        'quiz_command_id',
        'points',
        'time',
        'result',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quiz_id' => 'integer',
        'quiz_command_id' => 'integer',
        'points' => 'double',
        'time' => 'double',
        'result' => 'array',
    ];

    protected $with = ["quiz", "command"];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class,"quiz_id","id");
    }

    public function command(): BelongsTo
    {
        return $this->belongsTo(QuizCommand::class , "quiz_command_id","id");
    }


}
