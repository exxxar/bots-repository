<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrontPad extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'hook_url',
        'channel',
        'affiliate',
        'point',
        'pays',
        'statuses',
        'token',

    ];

    protected $hidden = [
        "token"
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'pays' => 'array',
        'statuses' => 'array',
    ];

    public $appends = ["is_active"];

    public function getIsActiveAttribute()
    {
        return !is_null($this->token);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

}
