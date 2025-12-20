<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Row;

class ProductFrontPadImport implements ToCollection
{
    protected $botId;

    public function __construct($botId)
    {
        $this->botId = $botId;
    }

    public function collection(Collection $rows)
    {


        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // пропускаем заголовок

            $category = trim($row[0] ?? '');
            $title = trim($row[1] ?? '');
            $price = trim($row[2] ?? '');
            $unit = trim($row[3] ?? '');
            $article = trim($row[4] ?? '');
            $description = trim($row[10] ?? '');

            if (!$title || !$price || !$article) {
                continue;
            }

            $tmpProduct = [
                'article' => null,
                'vk_product_id' => null,
                'frontpad_article' => $article,
                'title' => $title,
                'description' => $this->buildDescription($description, $price, $unit),
                'images' => [],
                'type' => 0,
                'old_price' => 0,
                'current_price' => (int)$price,
                'variants' => null,
                'in_stop_list_at' => null,
                'bot_id' => $this->botId,
                'deleted_at' => null,
            ];

            $product = Product::query()
                ->withTrashed()
                ->where("frontpad_article", $tmpProduct["frontpad_article"])
                ->where("bot_id", $this->botId)
                ->first();


            if (is_null($product))
                $product = Product::query()->create($tmpProduct);
            else
                $product->update($tmpProduct);

            $productCategorySection = ProductCategory::query()
                ->where("title", $category)
                ->where("bot_id", $this->botId)
                ->first();

            if (is_null($productCategorySection)) {
                $productCategorySection = ProductCategory::query()
                    ->create([
                        'title' => $category,
                        'bot_id' => $this->botId,
                    ]);

                $tmpCategoryForSync[] = $productCategorySection->id;

            }


            $product->productCategories()->sync($tmpCategoryForSync ?? []);

            $product->deleted_at = null;
            $product->in_stop_list_at = null;
            $product->save();

        }

    }

    private function buildDescription($desc, $price, $unit)
    {
        $parts = [];

        if ($desc) $parts[] = $desc;
        if ($price) $parts[] = "Цена: {$price} руб.";
        if ($unit) $parts[] = "Ед. изм.: {$unit}";

        return implode(" | ", $parts);
    }
}


