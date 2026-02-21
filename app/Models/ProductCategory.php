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
        // 1) Загружаем категории
        $categories = DB::table('product_categories as pc')
            ->select(
                'pc.id',
                'pc.title',
                'pc.order_position',
                'pc.is_active'
            )
            ->where('pc.bot_id', $botId)
            ->where('pc.is_active', 1)
            ->orderBy('pc.order_position')
            ->get();

        if ($categories->isEmpty()) {
            return [];
        }

        $categoryIds = $categories->pluck('id')->toArray();

        // 2) Загружаем товары всех категорий одним запросом
        $products = DB::table('products as p')
            ->select(
                'p.*',
                'ppc.product_category_id'
            )
            ->join('product_product_category as ppc', 'ppc.product_id', '=', 'p.id')
            ->whereIn('ppc.product_category_id', $categoryIds)
            ->where('p.bot_id', $botId)
            ->whereNull('p.deleted_at')
            ->whereNull('p.in_stop_list_at')
            ->orderBy('p.id')
            ->get()
            ->groupBy('product_category_id');

        // 3) Собираем структуру
        $result = [];

        foreach ($categories as $cat) {
            $catProducts = $products[$cat->id] ?? collect();

            $result[] = [
                'id' => $cat->id,
                'title' => $cat->title,
                'order_position' => $cat->order_position,
                'is_active' => $cat->is_active,
                'products' => $catProducts->take(8)->values(),
            ];
        }

        return $result;
    }







    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);

    }


}
