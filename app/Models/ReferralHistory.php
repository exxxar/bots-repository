<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReferralHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_sender_id',
        'user_recipient_id',
        'bot_id',
        'activated',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_sender_id' => 'integer',
        'user_recipient_id' => 'integer',
        'bot_id' => 'integer',
        'activated' => 'boolean',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function bot(): HasOne
    {
        return $this->hasOne(Bot::class);
    }

    public function userSender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userRecipient(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
