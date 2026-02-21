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
        $categories = ProductCategory::query()
            ->where('bot_id', $botId)
            ->where('is_active', true)
            ->orderBy('order_position')
            ->get();

        if ($categories->isEmpty()) {
            return [];
        }

        $categoryIds = $categories->pluck('id')->toArray();

        // 2) Загружаем товары всех категорий одним запросом
        $products = Product::query()
            ->select('products.*', 'ppc.product_category_id')
            ->join('product_product_category as ppc', 'ppc.product_id', '=', 'products.id')
            ->whereIn('ppc.product_category_id', $categoryIds)
            ->where('products.bot_id', $botId)
            ->whereNull('products.deleted_at')
            ->whereNull('products.in_stop_list_at')
            ->orderBy('products.id')
            ->get()
            ->groupBy('product_category_id');

        // 3) Собираем структуру
        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'title' => $category->title,
                'order_position' => $category->order_position,
                'is_active' => $category->is_active,
                'products' => ($products[$category->id] ?? collect())->take(8)->values(),
            ];
        }

        return $result;
    }








    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);

    }


}
