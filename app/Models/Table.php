<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Table extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'creator_id',
        'officiant_id',
        'number',
        'closed_at',
        'additional_services',
        'config',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'creator_id' => 'integer',
        'officiant_id' => 'integer',
        'closed_at' => 'timestamp',
        'config' => 'array',
        'additional_services' => 'array',
    ];

    protected $with = ["officiant", "clients"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function officiant(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(BotUser::class,'table_bot_user_clients');
    }
}
