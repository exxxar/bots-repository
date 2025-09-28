<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Str;

class ProductExport implements FromView
{
    private $botId;

    private $products;

    public function __construct($id)
    {
        $this->botId = $id;

        $this->products = Product::withTrashed()
            ->with(['bot', 'productOptions'])
            ->where("bot_id", $this->botId)
            ->get(); // Можно убрать withTrashed(), если не нужно
    }

    public function view(): View
    {
        return view('exports.products', [
            "products" => $this->products
        ]);
    }
}
