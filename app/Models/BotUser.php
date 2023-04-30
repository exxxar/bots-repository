<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'user_id',
        'parent_id',
        'is_vip',
        'is_admin',
        'is_work',

        'user_in_location',
        'location_comment',
        'is_deliveryman',
        'current_latitude',
        'current_longitude',

        'name',
        'phone',
        'email',
        'birthday',
        'age',
        'city',
        'country',
        'address',
        'sex',

        'fio_from_telegram',
        'telegram_chat_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'is_admin' => 'boolean',
        'is_work' => 'boolean',
        'user_in_location' => 'boolean',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
