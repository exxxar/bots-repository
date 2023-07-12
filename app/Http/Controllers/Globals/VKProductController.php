<?php

namespace App\Http\Controllers\Globals;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VKProductController extends Controller
{
    public function getVKAuthLink($botDomain)
    {
        $oauth = new VKOAuth();
        $client_id = env("VK_CLIENT_ID");
        $redirect_uri = env("APP_URL").'/global-scripts/shop/vk-callback';
        $display = VKOAuthDisplay::PAGE;
        $scope = [VKOAuthUserScope::MARKET];
        $state = $botDomain ?? 'secret_state_code';

        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);

        return response()->json([
            "url" => $browser_url
        ]);
    }

    public function callback(Request $request)
    {
        ini_set('max_execution_time', '30000');
        ini_set('memory_limit', '-1');
        if (!isset($request["code"]))
            return response()->noContent(400);


        $oauth = new VKOAuth();
        $client_id = env("VK_CLIENT_ID");
        $client_secret = env('VK_CLIENT_SECRET');
        $redirect_uri = env("APP_URL").'/global-scripts/shop/vk-callback';
        $code = $request->code;
        $state = $request->state; //bot domain

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        $access_token = $response['access_token'];


        $vk = new VKApiClient();
        $response = $vk->market()->get($access_token, [
            'owner_id' => -106641010,
            'need_variants' => 1,
            'extended' => 1
        ]);

        $vkProducts = ((object)$response)->items;

        $bot = Bot::query()
            ->where("bot_domain", $state)
            ->first();

        if (is_null($bot))
            return response()->noContent(404);

        foreach ($vkProducts as $vkProduct) {

            $variants = [];

            $vkVariants = $vkProduct->property_values ?? null;
            if (!is_null($vkVariants))
                foreach ($vkVariants as $variant) {
                    $variant = (object)$variant;
                    $variants[] = (object)[
                        "key" => $variant->property_name,
                        "value" => $variant->variant_name
                    ];
                }

            $vkProduct = (object)$vkProduct;

            $product = Product::query()
                ->where("vk_product_id", $vkProduct->id)
                ->first();

            if (is_null($product))
                $product = Product::query()->create([
                    'article' => $vkProduct->sku ?? null,
                    'vk_product_id' => $vkProduct->id,
                    'title' => $vkProduct->title,
                    'description' => $vkProduct->description,
                    'images' => [
                        $vkProduct->thumb_photo
                    ],
                    'type' => 0,
                    'old_price' => isset($vkProduct->price["old_amount"]) ? $vkProduct->price["old_amount"] / 100 : 0,
                    'current_price' => $vkProduct->price["amount"] / 100,
                    'variants' => empty($variants) ? null : $variants,
                    'in_stop_list_at' => $vkProduct->availability == 0 ? Carbon::now() : null,
                    'bot_id' => $bot->id,
                ]);
            else {
                $product->update([
                    'article' => $vkProduct->sku ?? null,
                    'title' => $vkProduct->title,
                    'description' => $vkProduct->description,
                    'images' => [
                        $vkProduct->thumb_photo
                    ],
                    'type' => 0,
                    'old_price' => isset($vkProduct->price["old_amount"]) ? $vkProduct->price["old_amount"] / 100 : 0,
                    'current_price' => $vkProduct->price["amount"] / 100,
                    'variants' => empty($variants) ? null : $variants,
                    'in_stop_list_at' => $vkProduct->availability == 0 ? Carbon::now() : null,
                ]);
            }

            $vkDimensions = $vkProduct->dimensions ?? null;

            if (!is_null($vkDimensions)) {
                $titles = [
                    "width" => "Ширина, мм",
                    "height" => "Высота, мм",
                    "length" => "Длина, мм",
                ];

                foreach ($vkDimensions as $key => $value) {

                    $option = ProductOption::query()
                        ->where("key", $key)
                        ->where("product_id", $product->id)
                        ->first();

                    if (is_null($option))
                        ProductOption::query()->create([
                            'key' => $key,
                            'title' => $titles[$key],
                            'value' => $value,
                            'section' => "Габариты",
                            'product_id' => $product->id,
                        ]);
                    else {
                        $option->value = $value;
                        $option->save();
                    }
                }

            }

            $vkWeight = $vkProduct->weight ?? null;

            if (!is_null($vkWeight)) {

                $option = ProductOption::query()
                    ->where("key", "weight")
                    ->where("product_id", $product->id)
                    ->first();

                if (is_null($option))
                    ProductOption::query()->create([
                        'key' => "weight",
                        'title' => "Вес, грамм",
                        'value' => $vkWeight,
                        'section' => "Вес",
                        'product_id' => $product->id,
                    ]);
                else {
                    $option->value = $vkWeight;
                    $option->save();
                }
            }

            $vkPhotos = $vkProduct->photos ?? null;

            if (!is_null($vkPhotos)) {
                $images = [];

                foreach ($vkPhotos as $photo) {
                    $photo = (object)$photo;
                    $images[] = $photo->sizes[count($photo->sizes) - 1]["url"];
                }

                $product->images = $images;
                $product->save();
            }

            $vkCategory = $vkProduct->category ?? null;

            if (!is_null($vkCategory)) {
                $vkCategory = (object)$vkCategory;

                $productCategory = ProductCategory::query()
                    ->where("title", $vkCategory->name)
                    ->first();

                if (is_null($productCategory))
                    $productCategory = ProductCategory::query()
                        ->create([
                            'title' => $vkCategory->name,
                            'bot_id' => $bot->id,
                        ]);


                $vkCategorySection = (object)$vkCategory->section;

                $productCategorySection = ProductCategory::query()
                    ->where("title", $vkCategorySection->name)
                    ->first();

                if (is_null($productCategorySection))
                    $productCategorySection = ProductCategory::query()
                        ->create([
                            'title' => $vkCategorySection->name,
                            'bot_id' => $bot->id,
                        ]);

                $product->productCategories()->sync([$productCategorySection->id, $productCategory->id]);
            }
        }

        return response()->noContent();
        // dd($response);
    }
}
