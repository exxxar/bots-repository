<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
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
        'delivery_service_info',
        'deliveryman_info',
        'product_details',
        'product_count',
        'summary_price',
        'delivery_price',
        'delivery_range',
        'deliveryman_latitude',
        'deliveryman_longitude',
        'delivery_note',
        'receiver_name',
        'receiver_phone',
        'receiver_get_id',
        'status',
        'order_type',
        'payed_at',
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
        'delivery_service_info' => 'array',
        'deliveryman_info' => 'array',
        'product_details' => 'array',
        'summary_price' => 'double',
        'delivery_price' => 'double',
        'delivery_range' => 'double',
        'deliveryman_latitude' => 'double',
        'deliveryman_longitude' => 'double',
        'receiver_get_id' => 'integer',
        'payed_at' => 'timestamp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function geo(): BelongsTo
    {
        return $this->belongsTo(Geo::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }


}
