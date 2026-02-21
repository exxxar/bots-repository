<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class ProductCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'bot_id',
        'is_active',
        'order_position',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'order_position' => 'integer',
        'is_active' => 'boolean',
    ];



    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public static function getCategoriesWithProducts(int $botId)
    {
        $rows = DB::table('product_categories as pc')
            ->select([
                'pc.id as category_id',
                'pc.title as category_title',
                'pc.order_position',
                'pc.is_active',

                'p.id as product_id',
                'p.title as product_title',
                'p.price',
                'p.photo',
                'p.bot_id',

                DB::raw('ROW_NUMBER() OVER (
                PARTITION BY pc.id
                ORDER BY p.id
            ) as rn'),

                DB::raw('(SELECT COUNT(*)
                      FROM products p2
                      JOIN product_category_product pcp2
                        ON pcp2.product_id = p2.id
                      WHERE pcp2.product_category_id = pc.id
                        AND p2.deleted_at IS NULL
                        AND p2.in_stop_list_at IS NULL
                    ) AS products_count')
            ])
            ->join('product_category_product as pcp', 'pcp.product_category_id', '=', 'pc.id')
            ->join('products as p', 'p.id', '=', 'pcp.product_id')
            ->where('pc.bot_id', $botId)
            ->where('pc.is_active', true)
            ->whereNull('p.deleted_at')
            ->whereNull('p.in_stop_list_at')
            ->having('rn', '<=', 8)
            ->orderBy('pc.order_position')
            ->get();

        // Группируем результат
        $categories = [];

        foreach ($rows as $row) {
            $id = $row->category_id;

            if (!isset($categories[$id])) {
                $categories[$id] = [
                    'id' => $row->category_id,
                    'title' => $row->category_title,
                    'order_position' => $row->order_position,
                    'is_active' => $row->is_active,
                    'products_count' => $row->products_count,
                    'products' => []
                ];
            }

            if ($row->product_id) {
                $categories[$id]['products'][] = [
                    'id' => $row->product_id,
                    'title' => $row->product_title,
                    'price' => $row->price,
                    'photo' => $row->photo,
                    'bot_id' => $row->bot_id,
                ];
            }
        }

        return array_values($categories);
    }




    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);

    }


}
