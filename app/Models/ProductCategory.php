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
        $categories = DB::table('product_categories as pc')
            ->select(
                'pc.id',
                'pc.title',
                'pc.order_position',
                'pc.is_active',
                DB::raw('COUNT(p.id) as products_count')
            )
            ->leftJoin('product_product_category as ppc', 'ppc.product_category_id', '=', 'pc.id')
            ->leftJoin('products as p', function ($join) {
                $join->on('p.id', '=', 'ppc.product_id')
                    ->whereNull('p.deleted_at')
                    ->whereNull('p.in_stop_list_at');
            })
            ->where('pc.bot_id', $botId)
            ->where('pc.is_active', 1)
            ->groupBy('pc.id')
            ->having('products_count', '>', 0)
            ->orderBy('pc.order_position')
            ->get();

        $products = DB::table('products as p')
            ->select(
                'p.*',
                'ppc.product_category_id'
            )
            ->join('product_product_category as ppc', 'ppc.product_id', '=', 'p.id')
            ->where('p.bot_id', $botId)
            ->whereNull('p.deleted_at')
            ->whereNull('p.in_stop_list_at')
            ->orderBy('p.id')
            ->get()
            ->groupBy('product_category_id');

        $result = [];

        foreach ($categories as $cat) {
            $catProducts = $products[$cat->id] ?? collect();

            $result[] = [
                'id' => $cat->id,
                'title' => $cat->title,
                'order_position' => $cat->order_position,
                'is_active' => $cat->is_active,
                'products_count' => $cat->products_count,
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
