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
        'deliveryman_id',
        'customer_id',
        'delivery_service_info',
        'deliveryman_info',
        'product_details',
        'product_count',
        'summary_price',
        'delivery_price',
        'delivery_range',
        'deliveryman_latitude',
        'deliveryman_longitude',
        "service_rating",
        "service_review",
        'delivery_note',
        'receiver_name',
        'receiver_phone',
        "address",
        "receiver_latitude",
        "receiver_longitude",
        'status',
        'order_type',
        'is_cashback_crediting',
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
        'deliveryman_id' => 'integer',
        'customer_id' => 'integer',
        'is_cashback_crediting' => 'boolean',
        'delivery_service_info' => 'array',
        'deliveryman_info' => 'array',
        'product_details' => 'array',
        'summary_price' => 'double',
        'delivery_price' => 'double',
        'delivery_range' => 'double',


        "receiver_latitude"=> 'double',
        "receiver_longitude"=> 'double',

        'deliveryman_latitude' => 'double',
        'deliveryman_longitude' => 'double',
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
