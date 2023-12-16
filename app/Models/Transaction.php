<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bot_user_id',
        'bot_id',
        'payload',
        'currency',
        'total_amount',
        'status',
        'order_info',
        'products_info',
        'shipping_address',
        'telegram_payment_charge_id',
        'provider_payment_charge_id',
        'completed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'bot_id' => 'integer',
        'order_info' => 'array',
        'products_info' => 'array',
        'shipping_address' => 'array',
        'completed_at' => 'timestamp',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
