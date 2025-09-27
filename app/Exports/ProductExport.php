<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Str;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    private $botId;

    public function __construct($id)
    {
        $this->botId = $id;
    }

    /**
     * Получение коллекции продуктов.
     */
    public function collection()
    {
        return Product::withTrashed()
            ->with(['bot', 'productOptions'])
            ->where("bot_id", $this->botId)
            ->get(); // Можно убрать withTrashed(), если не нужно
    }

    /**
     * Заголовки столбцов.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Article',
            'VK Product ID',
            'Frontpad Article',
            'Iiko Article',
            'Title',
            'Delivery Terms',
            'Description',
            'Images',
            'Type',
            'Rating',
            'Old Price',
            'Current Price',
            'Variants',
            'In Stop List At',
            'Not For Delivery',
            'Dimension',
            'Bot ID',
            'Deleted At',
        ];
    }

    /**
     * Преобразование модели в массив строк.
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->article,
            $product->vk_product_id,
            $product->frontpad_article,
            $product->iiko_article,
            $product->title,
            $product->delivery_terms,
            $product->description,
          print_r($product->images, true),
            $product->type,
            $product->rating,
            $product->old_price,
            $product->current_price,
            json_encode($product->variants),
            optional($product->in_stop_list_at)->format('Y-m-d H:i:s'),
            $product->not_for_delivery ? 'Yes' : 'No',
            json_encode($product->dimension),
            $product->bot_id,
            optional($product->deleted_at)->format('Y-m-d H:i:s'),
        ];
    }
}
