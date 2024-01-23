<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuizCommand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'logo',
        'captain_id',
        'creator_id'

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    protected $with = ["players",/*"captain","creator"*/];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(BotUser::class);
    }

    public function captain(): HasOne
    {
        return $this->hasOne(BotUser::class);
    }

    public function creator(): HasOne
    {
        return $this->hasOne(BotUser::class);
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class);
    }
}
