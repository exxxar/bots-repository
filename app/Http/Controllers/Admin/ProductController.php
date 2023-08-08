<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Bot;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getProductsByIds(Request $request)
    {
        $request->validate([
            "ids" => "required|array"
        ]);

        $products = Product::query()
            ->whereIn("id", $request->ids)
            ->get();

        return new ProductCollection($products);
    }

    public function index(Request $request)
    {
        $search = $request->search ?? null;

        $products = Product::query()
            ->with(["productCategories", "productOptions"]);

        if (!is_null($search))
            $products = $products
                ->where("title", "like", "%$search%")
                ->orWhere("description", "like", "%$search%");


        $products = $products
            ->orderBy("created_at", "DESC")
            ->paginate(20);

        return new ProductCollection($products);
    }

    public function getCategories(Request $request)
    {
        $request->validate([
            "bot_id" => "required"
        ]);

        $categories = ProductCategory::query()
            ->where("bot_id", $request->bot_id)
            ->get();

        return new ProductCategoryCollection($categories);
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

    public function saveProduct(Request $request)
    {

        $request->validate([
            "article" => "required",
            "vk_product_id" => "",
            "title" => "required",
            "description" => "required",
            "images" => "",
            "type" => "required",
            "old_price" => "",
            "current_price" => "required",
            "in_stop_list_at" => "",
            "bot_id" => "required",
            "photos" => "",
            "variants" => "",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        if (is_null($bot))
            return response()->noContent(404);

        /*     options: [],
        variants: [],
                    reviews: [],
                    categories: [],*/

        $photos = [];

        if ($request->hasFile('photos')) {
            $files = $request->file('photos');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $slug = $bot->company->slug;
                $file->storeAs("/public/companies/$slug/$imageName");
                $photos[] = $imageName;
            }


        }

        $images = $request->images ?? null;

        if (!is_null($images))
            $images = json_decode($images);

        $images = count($photos) == 0 ? (is_array($images) ? $images : null) : [...$photos, ...$images];

        $variants = $request->variants ?? null;

        if (!is_null($variants))
            $variants = json_decode($variants);

        $removedOptions = $request->removed_options ?? null;

        if (!is_null($removedOptions)) {
            $removedOptions = json_decode($removedOptions);
            foreach ($removedOptions as $key => $id) {
                $tmpOption = ProductOption::query()
                    ->where("id", $id)
                    ->first();

                if (!is_null($tmpOption)) {
                    $tmpOption->delete();
                }

            }
        }

        $productId = $request->id ?? null;

        $tmp = [
            'article' => $request->article,
            'vk_product_id' => $request->vk_product_id ?? null,
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
            'images' => $images,
            'type' => $request->type ?? 0,
            'old_price' => $request->old_price ?? 0,
            'current_price' => $request->current_price ?? 0,
            'variants' => $variants,
            'in_stop_list_at' => ($request->in_stop_list_at ?? false) == "true" ? Carbon::now() : null,
            'bot_id' => $request->bot_id ?? null,
        ];

        if (!is_null($productId)) {
            $product = Product::query()
                ->with(["productCategories", "productOptions"])
                ->where("id", $productId)
                ->first();

            $product->update($tmp);
        } else
            $product = Product::query()
                ->with(["productCategories", "productOptions"])
                ->create($tmp);


        $options = $request->options ?? null;

        if (!is_null($options)) {
            $options = json_decode($options);
            //dd($options);
            foreach ($options as $option) {
                $option = (object)$option;
                $key = Str::uuid();

                if (is_null($option->id))
                    ProductOption::query()
                        ->create([
                            'key' => $key,
                            'title' => $option->title,
                            'value' => $option->value,
                            'section' => $option->section,
                            'product_id' => $product->id
                        ]);
                else
                    ProductOption::query()
                        ->find($option->id)
                        ->update([
                            'title' => $option->title,
                            'value' => $option->value,
                            'section' => $option->section,
                        ]);
            }

        }

        $categories = $request->categories ?? null;

        if (!is_null($categories)) {

            $tmp = [];
            $categories = json_decode($categories);


            foreach ($categories as $category) {
                $tmpCategory = ProductCategory::query()
                    ->where("id", $category)
                    ->first();

                if (is_null($tmpCategory))
                    $tmpCategory = ProductCategory::query()
                        ->create([
                            'title' => $category,
                            'bot_id' => $bot->id
                        ]);


                $tmp[] = $tmpCategory->id;
            }
            $product->productCategories()->sync($tmp);


        }


        // ;->with(["productCategories","productOptions"])
        return \response()->noContent();

    }

    public function destroy(Request $request, $productId)
    {
        $product = Product::query()
            ->with(["productCategories", "productOptions"])
            ->find($productId);

        if (is_null($product))
            return response()->noContent(404);

        $options = $product->productOptions;

        if (!empty($options))
            foreach ($options as $option)
                $option->delete();

        $categories = $product->productCategories;

        $tmp = [];
        if (!empty($categories))
        foreach ($categories as $category)
            $tmp[] = $category->id;

        $product->productCategories()->detach($tmp);

        $product->delete();

        return response()->noContent();
    }

    public function duplicate(Request $request, $productId)
    {
        $product = Product::query()
            ->with(["productCategories", "productOptions"])
            ->find($productId);

        if (is_null($product))
            return response()->noContent(404);

        $newProduct = $product->replicate();
        $newProduct->save();

        if (!empty($product->productCategories)) {
            $tmp = [];
            foreach ($product->productCategories as $category)
                $tmp[] = $category->id;

            $newProduct->productCategories()->sync($tmp);
        }

        if (!empty($product->productOptions))
            foreach ($product->productOptions as $option)
                ProductOption::query()
                    ->create([
                        'key' => Str::uuid(),
                        'title' => $option->title,
                        'value' => $option->value,
                        'section' => $option->section,
                        'product_id' => $newProduct->id
                    ]);


        return response()->noContent();
    }

}
