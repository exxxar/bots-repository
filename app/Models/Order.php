<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'need_delivery',
        'delivery_address',
        'comment',
        'summary_price',
        'deliveryman_id',
        'payed_at',
    ];

    protected $with = ["baskets"];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'need_delivery' => 'boolean',
        'summary_price' => 'double',
        'payed_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public function baskets(){
        return $this->hasMany(Basket::class,"order_id","id");
    }
}
