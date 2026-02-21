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
        $rows = DB::select("
        SELECT
            pc.id AS category_id,
            pc.title AS category_title,
            pc.order_position,
            pc.is_active,

            p.id AS product_id,
            p.title AS product_title,
            p.current_price,
            p.old_price,
            p.images,
            p.bot_id,

            (
                SELECT COUNT(*)
                FROM products p2
                JOIN product_product_category ppc2
                    ON ppc2.product_id = p2.id
                WHERE ppc2.product_category_id = pc.id
                  AND p2.deleted_at IS NULL
                  AND p2.in_stop_list_at IS NULL
            ) AS products_count

        FROM product_categories pc

        JOIN product_product_category ppc
            ON ppc.product_category_id = pc.id

        JOIN products p
            ON p.id = ppc.product_id

        WHERE pc.bot_id = ?
          AND pc.is_active = 1
          AND p.deleted_at IS NULL
          AND p.in_stop_list_at IS NULL

          -- Берём только первые 8 товаров на категорию
          AND (
                SELECT COUNT(*)
                FROM product_product_category ppc3
                JOIN products p3 ON p3.id = ppc3.product_id
                WHERE ppc3.product_category_id = pc.id
                  AND p3.deleted_at IS NULL
                  AND p3.in_stop_list_at IS NULL
                  AND p3.id <= p.id
            ) <= 8

        ORDER BY pc.order_position, p.id
    ", [$botId]);

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
                    'current_price' => $row->current_price,
                    'old_price' => $row->old_price,
                    'images' => $row->images,
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
