<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotSecurityResource;
use App\Models\Bot;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use mysql_xdevapi\Exception;
use VK\Client\VKApiClient;
use VK\Exceptions\VKException;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VKProductController extends Controller
{
    //protected string $vkUrl;

    protected $fpProducts = null;

    public function getVKAuthLink(Request $request)
    {
        $bot = $request->bot ?? null;

        // $this->vkUrl = $request->url ?? null;
        $botDomain = is_null($bot) ?
            $request->bot_domain ?? $request->botDomain ?? null :
            $bot->bot_domain ?? null;

        $oauth = new VKOAuth();
        $client_id = env("VK_CLIENT_ID");
        $redirect_uri = env("APP_URL") . '/bot-client/vk-callback';
        $display = VKOAuthDisplay::PAGE;
        $scope = [VKOAuthUserScope::MARKET];
        $state = $botDomain ?? 'secret_state_code';

        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);

        return response()->json([
            "url" => $browser_url
        ]);
    }

    protected function findFrontPadProduct($test): ?object
    {
        $index = null;

        foreach ($this->fpProducts["name"] as $key=>$name)
        {
            if ($name == $test ) {
                $index = $key;
                break;
            }
            //$index++;
        }

        if (is_null($index))
            return null;

        Log::info("product=>".($this->fpProducts["product_id"][$index] ?? '-')."----".($this->fpProducts["name"][$index]??'-'));

        return (object)[
            "name" => $test,
            "index" => $index,
            "id" => $this->fpProducts["product_id"][$index] ?? '-'
        ];
    }

    protected function importProducts($vkProducts, $bot, $album, &$results)
    {
        foreach ($vkProducts as $vkProduct) {

            $tmpCategoryForSync = [];

            $variants = [];

            $results->total_product_count++;

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
                ->where("bot_id", $bot->id)
                ->first();



            if (!is_null($this->fpProducts ?? null))
            {
                Log::info("VK PRODUCT $vkProduct->title");
                $fpObject = $this->findFrontPadProduct($vkProduct->title);

                if (!is_null($fpObject))
                    $results->total_frontpad_count++;
            }


            if (is_null($product)) {
                $product = Product::query()->create([
                    'article' => $vkProduct->sku ?? null,
                    'vk_product_id' => $vkProduct->id,
                    'frontpad_article' => $fpObject->id ?? null,
                    'title' => $vkProduct->title,
                    'description' => $vkProduct->description,
                    'images' => [
                        $vkProduct->thumb_photo
                    ],
                    'type' => 0,
                    'old_price' => isset($vkProduct->price["old_amount"]) ? $vkProduct->price["old_amount"] / 100 : 0,
                    'current_price' => $vkProduct->price["amount"] / 100,
                    'variants' => empty($variants) ? null : $variants,
                    'in_stop_list_at' => $vkProduct->availability == 0 ?  null : Carbon::now(),
                    'bot_id' => $bot->id,
                ]);

                $results->created_product_count++;
            } else {
                $product->update([
                    'article' => $vkProduct->sku ?? null,
                    'frontpad_article' => $fpObject->id ?? null,
                    'title' => $vkProduct->title,
                    'description' => $vkProduct->description,
                    'images' => [
                        $vkProduct->thumb_photo
                    ],
                    'type' => 0,
                    'old_price' => isset($vkProduct->price["old_amount"]) ? $vkProduct->price["old_amount"] / 100 : 0,
                    'current_price' => $vkProduct->price["amount"] / 100,
                    'variants' => empty($variants) ? null : $variants,
                    'in_stop_list_at' => $vkProduct->availability == 0 ?  null : Carbon::now(),
                ]);

                $results->updated_product_count++;
            }

            $vkDimensions = $vkProduct->dimensions ?? null;

            if (!is_null($vkDimensions)) {
                $titles = [
                    "width" => "Ширина, мм",
                    "height" => "Высота, мм",
                    "length" => "Длина, мм",
                ];

                foreach ($vkDimensions as $key => $value) {

                    if ($value == 0)
                        continue;

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

            Log::info("categories=>" . print_r($vkCategory, true));
            if (!is_null($vkCategory)) {
                $vkCategory = (object)$vkCategory;


                $productCategory = ProductCategory::query()
                    ->where("title", $vkCategory->name)
                    ->where("bot_id", $bot->id)
                    ->first();

                if (is_null($productCategory))
                    $productCategory = ProductCategory::query()
                        ->create([
                            'title' => $vkCategory->name,
                            'bot_id' => $bot->id,
                        ]);

                $tmpCategoryForSync[] = $productCategory->id;

                $vkCategorySection = (object)$vkCategory->section;

                $productCategorySection = ProductCategory::query()
                    ->where("title", $vkCategorySection->name)
                    ->where("bot_id", $bot->id)
                    ->first();

                if (is_null($productCategorySection))
                    $productCategorySection = ProductCategory::query()
                        ->create([
                            'title' => $vkCategorySection->name,
                            'bot_id' => $bot->id,
                        ]);

                $tmpCategoryForSync[] = $productCategorySection->id;


                if (!is_null($album)) {
                    $productCategoryAlbum = ProductCategory::query()
                        ->where("title", $album->title)
                        ->where("bot_id", $bot->id)
                        ->first();

                    if (is_null($productCategoryAlbum))
                        $productCategoryAlbum = ProductCategory::query()
                            ->create([
                                'title' => $album->title,
                                'bot_id' => $bot->id,
                            ]);


                    $tmpCategoryForSync[] = $productCategoryAlbum->id;

                    //    Log::info("album" . print_r($productCategoryAlbum->toArray(), true));
                }

                if (count($tmpCategoryForSync) > 0) {
                    //Log::info("tmpCategoryForSync=>".print_r($tmpCategoryForSync,true));
                    $product->productCategories()->sync($tmpCategoryForSync);
                }

            }
        }
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
        $redirect_uri = env("APP_URL") . '/bot-client/vk-callback';
        $code = $request->code;
        $state = $request->state; //bot domain


        $bot = Bot::query()
            ->with(["frontPad"])
            ->where("bot_domain", $state)
            ->first();


        if (is_null($bot))
            return response()->noContent(404);

        if (is_null($bot->vk_shop_link))
            return response()->noContent(404);

       /* $products = Product::query()
            ->where("bot_id", $bot->id)
            ->get();

        foreach ($products as $product) {
            $product->in_stop_list_at = Carbon::now();
            $product->save();
        }*/


        $this->fpProducts = !is_null($bot->frontPad ?? null) && !is_null($bot->frontPad->token ?? null) ?
            BusinessLogic::frontPad()
                ->setBot($bot)
                ->getProducts() : null;

        Log::info("loaded products => ".print_r( $this->fpProducts, true));

        Log::info("1test $client_id $client_secret $redirect_uri $code $state");

        $tmpScreenName = substr($bot->vk_shop_link, strpos($bot->vk_shop_link, "https://vk.com/") + strlen("https://vk.com/"));

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        $access_token = $response['access_token'] ?? null;
        Log::info("2test $client_id $client_secret $redirect_uri $code " . $response['access_token']);


        $vk = new VKApiClient();

        try {

            Log::info("3test $tmpScreenName");
            $response = $vk->utils()->resolveScreenName($access_token, [
                'screen_name' => $tmpScreenName ?? null,
            ]);


        } catch (\Exception $e) {
            return response()->noContent(404);

        }

        $data = ((object)$response);
        Log::info("4test " . print_r($data, true));
        if (is_null($data))
            return response()->noContent(400);

        if ($data->type != "group" && $data->type != "page")
            return response()->noContent(400);


        Log::info("access_token:$access_token");

        $results = (object)[
            "total_product_count" => 0,
            "created_product_count" => 0,
            "updated_product_count" => 0,
            "total_frontpad_count" => 0,
        ];

        try {
            $response = $vk->market()->getAlbums($access_token, [
                'owner_id' => "-$data->object_id",
            ]);

            $vkAlbums = ((object)$response)->items;

            if (count($vkAlbums) > 0)
                foreach ($vkAlbums as $album) {


                    $album = (object)$album;

                    $response = $vk->market()->get($access_token, [
                        'owner_id' => "-$data->object_id",
                        'album_id' => $album->id,
                        'need_variants' => 1,
                        'count' => 200,
                        'extended' => 1
                    ]);


                    $vkProducts = ((object)$response)->items;

                    $this->importProducts($vkProducts, $bot, $album, $results);

                }
            else {
                $response = $vk->market()->get($access_token, [
                    'owner_id' => "-$data->object_id",
                    'need_variants' => 1,
                    'count' => 200,
                    'with_disabled' => 1,
                    'extended' => 1
                ]);


                $vkProducts = ((object)$response)->items;

                $this->importProducts($vkProducts, $bot, null, $results);
            }

        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getLine());
            Inertia::setRootView("shop");

            return Inertia::render('Result', [
                'message' => "Ошибка добавления товаров!",
            ]);
        }

        $tmpClearedCategories = ProductCategory::query()
            ->with(["products"])
            ->where("bot_id", $bot->id)
            ->has("products","=",0)
            ->get();

        foreach ($tmpClearedCategories as $tmpClearedCategory)
            $tmpClearedCategory->delete();

        Inertia::setRootView("shop");

        return Inertia::render('Result', [
            'message' => "Товары успешно добавлены!",
            'data' => json_encode($results)
        ]);
        // dd($response);
    }
}
