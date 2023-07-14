<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;

        $products = Product::query();

        if (!is_null($search))
            $products = $products
                ->where("title", "like", "%$search%")
                ->orWhere("description", "like", "%$search%");


        $products = $products
            ->orderBy("created_at","DESC")
            ->paginate(20);

        return new ProductCollection($products);
    }

    public function getProduct(Request $request, $productId)
    {

        $product = Product::query()
            ->where("id", $productId)
            ->first();

        if (is_null($product))
            return response()->noContent(404);

        return new ProductResource($product);
    }

    public function randomProducts(Request $request)
    {
        $request->validate([
            "bot_id" => "required"
        ]);

        $products = Product::query()
            ->where("bot_id", $request->bot_id)
            ->get();

        return new ProductCollection($products->random(10));

    }

}
