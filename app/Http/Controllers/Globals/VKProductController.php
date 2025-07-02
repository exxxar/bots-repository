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

    protected $tmpProducts = [];

    /**
     * @throws \HttpException
     */
    public function getVKAuthLink(Request $request)
    {
        $bot = $request->bot ?? null;

        // $this->vkUrl = $request->url ?? null;
        $botDomain = is_null($bot) ?
            $request->bot_domain ?? $request->botDomain ?? null :
            $bot->bot_domain ?? null;

        if (is_null($botDomain))
            throw new \HttpException("Бот не найден!", 400);

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

        if (is_null($this->fpProducts ?? null))
            return null;


        foreach ($this->fpProducts["name"] ?? [] as $key => $name) {
            $chars = ['"', "'", "`", "(", ")", "-"];

            $preparedName1 = mb_strtolower(str_replace($chars, "", mb_strtolower($name)));
            $preparedName2 = mb_strtolower(str_replace($chars, "", mb_strtolower($test)));

            if ($preparedName1 == $preparedName2) {

                $index = $key;
                break;
            }
            //$index++;
        }

        if (is_null($index))
            return null;


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


            }

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
                ->withTrashed()
                ->where("vk_product_id", $vkProduct->id)
                ->where("bot_id", $bot->id)
                ->first();


            if (!is_null($this->fpProducts ?? null)) {

                $fpObject = $this->findFrontPadProduct($vkProduct->title);

                if (!is_null($fpObject))
                    $results->total_frontpad_count++;
                else {
                    $fpnfi = $results->front_pad_not_found_items ?? [];
                    $fpnfi[] = $vkProduct->title;
                    $results->front_pad_not_found_items = $fpnfi;
                }
            }

            $tmpProduct = [
                'article' => $vkProduct->sku ?? null,
                'vk_product_id' => $vkProduct->id ?? '-',
                'frontpad_article' => $fpObject->id ?? null,
                'title' => $vkProduct->title ?? '-',
                'description' => $vkProduct->description ?? '-',
                'images' => [
                    $vkProduct->thumb_photo
                ],
                'type' => 0,
                'old_price' => isset($vkProduct->price["old_amount"]) ? $vkProduct->price["old_amount"] / 100 : 0,
                'current_price' => $vkProduct->price["amount"] / 100,
                'variants' => empty($variants) ? null : $variants,
                'in_stop_list_at' => $vkProduct->availability == 0 ? null : Carbon::now(),
                'bot_id' => $bot->id,
                'deleted_at' => null
            ];


            if (is_null($product)) {
                $product = Product::query()->create($tmpProduct);
                $results->created_product_count++;
            } else {
                $product->update($tmpProduct);
                $results->updated_product_count++;
            }

            if (!in_array($product->id, $this->tmpProducts))
                $this->tmpProducts[] = $product->id ?? null;
            else {
                $product->productCategories()->attach($tmpCategoryForSync);
                continue;
            };

            $dimension = $product->dimension ?? [];

            $vkDimensions = $vkProduct->dimensions ?? null;

            if (!is_null($vkDimensions)) {
                foreach ($vkDimensions as $key => $value) {
                    $dimension[$key] = $value ?? $dimension[$key] ?? 0;
                }

            }

            $vkWeight = $vkProduct->weight ?? null;

            if (!is_null($vkWeight)) {
                $dimension["weight"] = $vkWeight;
            }

            $product->dimension = $dimension;
            $product->save();

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


            }

            if (count($tmpCategoryForSync) > 0)
                $product->productCategories()->sync($tmpCategoryForSync);

        }
    }

    public function shopMode($bot, $code)
    {
        $oauth = new VKOAuth();
        $client_id = env("VK_CLIENT_ID");
        $client_secret = env('VK_CLIENT_SECRET');
        $redirect_uri = env("APP_URL") . '/bot-client/vk-callback';


        $products = Product::query()
            ->where("bot_id", $bot->id)
            ->get();

        foreach ($products as $product) {
            Log::info("ПРОДУКТ => " . $product->id . " " . $product->title . " " . $product->bot_id);
            $product->in_stop_list_at = Carbon::now();
            $product->deleted_at = Carbon::now();
            $product->save();
        }


        $this->fpProducts = !is_null($bot->frontPad ?? null) && !is_null($bot->frontPad->token ?? null) ?
            BusinessLogic::frontPad()
                ->setBot($bot)
                ->getProducts() : null;


        $tmpScreenName = substr($bot->vk_shop_link, strpos($bot->vk_shop_link, "https://vk.com/") + strlen("https://vk.com/"));

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        $access_token = $response['access_token'] ?? null;

        $vk = new VKApiClient();

        try {

            $response = $vk->utils()->resolveScreenName($access_token, [
                'screen_name' => $tmpScreenName ?? null,
            ]);


        } catch (\Exception $e) {
            return response()->noContent(404);

        }

        $data = ((object)$response);

        if (is_null($data))
            return response()->noContent(400);

        $type = ($data->type ?? null);
        if ($type != "group" && $type != "page")
            return response()->noContent(400);


        $results = (object)[
            "total_product_count" => 0,
            "created_product_count" => 0,
            "updated_product_count" => 0,
            "total_frontpad_count" => 0,
            "front_pad_not_found_items" => [],
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
                        'with_disabled' => 1,
                        'extended' => 1
                    ]);


                    $vkProducts = ((object)$response)->items;

                    // Log::info("Альбом=>".$album->title." товары в альбоме ".print_r($vkProducts, true));

                    $this->importProducts($vkProducts, $bot, $album, $results);

                    sleep(2);
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

            /// Log::info("all product ids=>" . print_r(array_values($this->tmpProducts), true));
        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getLine());
            Inertia::setRootView("shop");

            return Inertia::render('V2/Result', [
                'message' => "Ошибка добавления товаров!",
            ]);
        }

        /*$tmpClearedCategories = ProductCategory::query()
            ->with(["products"])
            ->where("bot_id", $bot->id)
            ->has("products","=",0)
            ->get();

        foreach ($tmpClearedCategories as $tmpClearedCategory)
            $tmpClearedCategory->delete();*/

        Inertia::setRootView("shop");

        return Inertia::render('V2/Result', [
            'message' => "Товары успешно добавлены!",
            'data' => json_encode($results)
        ]);
    }

    public function marketplaceMode($bot, $code)
    {
        $oauth = new VKOAuth();
        $client_id = env("VK_CLIENT_ID");
        $client_secret = env('VK_CLIENT_SECRET');
        $redirect_uri = env("APP_URL") . '/bot-client/vk-callback';

        $tmpScreenName = substr($bot->vk_shop_link, strpos($bot->vk_shop_link, "https://vk.com/") + strlen("https://vk.com/"));

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        $access_token = $response['access_token'] ?? null;

        $vk = new VKApiClient();

        try {

            $response = $vk->utils()->resolveScreenName($access_token, [
                'screen_name' => $tmpScreenName ?? null,
            ]);


        } catch (\Exception $e) {
            return response()->noContent(404);

        }

        $data = ((object)$response);

        if (is_null($data))
            return response()->noContent(400);

        if ($data->type != "group" && $data->type != "page")
            return response()->noContent(400);

        /*


                    Schema::disableForeignKeyConstraints();
                    MenuCategory::truncate();
                    RestoranInCategory::truncate();
                    RestMenu::truncate();
                    Schema::enableForeignKeyConstraints();


                    $token = $auth->getToken($request->get('code'));

                    $api = new Client('5.131');
                    $api->setDefaultToken($token);

                    $tmp_ids = [["id" => "-136275935", "base" => true]];
                    $restorans = Restoran::select(["vk_group_id"])->whereNotNull("vk_group_id")->get();

                    if (count($restorans) > 0)
                        foreach ($restorans as $rest)
                            array_push($tmp_ids, ["id" => $rest->vk_group_id, "base" => false]
                            );

                    foreach ($tmp_ids as $tmp_id) {
                        $response = $api->request('market.getAlbums', [
                            'owner_id' => $tmp_id["id"],
                            'count' => 50
                        ]);


                        foreach ($response["response"]["items"] as $item) {
                            //echo $item["id"].$item["title"]." ".$item["photo"]["photo_807"]."<br>";

                            $response2 = $api->request('market.get', [
                                'owner_id' => $tmp_id["id"],
                                'album_id' => $item["id"],
                                'count' => 200,
                            ]);


                            foreach ($response2["response"]["items"] as $item2) {
                                //echo $item2["description"]." ".$item2["price"]["text"]." ".$item2["thumb_photo"]." ".$item2["title"]."<br>";


                                //preg_match_all('|\d+|', $item2["description"], $matches);
                                preg_match_all('/(#\w+)/u', $item2["description"], $matches);

                                // $count = $matches[0][0] ?? 0;
                                //dd($matches);


                                $cat = count($matches[0]) > 0 ? $matches[0][0] : "#безкатегории";


                                $category = MenuCategory::where("name", $cat)->first();
                                if (is_null($category)) {
                                    $category = MenuCategory::create([
                                        "name" => $cat
                                    ]);
                                }


                                //preg_match_all('|\d+|', $item2["price"]["text"], $matches);

                                $price = intval($item2["price"]["amount"]) / 100;//$matches[0][0] ?? 0;
                                $tmp_old_price = isset($item2["price"]["old_amount"]) ? intval($item2["price"]["old_amount"]) / 100 : 0;

                                $rest = Restoran::with(["categories"])
                                    ->where("name", $item["title"])->distinct('parent_id')->first();

                                if (is_null($rest))
                                    continue;


                                $description = $item2["description"];

                                preg_match_all('/([0-9]+).грамм/i', $description, $media);

                                $weight = count($matches) >= 2 ? ($media[1][0] ?? 0) : 0;

                                $food_status = [
                                    "Акция!" => FoodStatusEnum::Promotion,
                                    "Скидка!" => FoodStatusEnum::Promotion,
                                    "Топ!" => FoodStatusEnum::InTheTop,
                                    "Хит продаж!" => FoodStatusEnum::BestSeller,
                                    "Новинка!" => FoodStatusEnum::NewFood,
                                    "На вес!" => FoodStatusEnum::WeightFood,
                                ];

                                $food_status_index = null;

                                foreach ($food_status as $key => $status)
                                    if (mb_strpos(mb_strtolower($description), mb_strtolower($key)))
                                        $food_status_index = $key;

                                $product = RestMenu::create([
                                    'food_name' => $item2["title"],
                                    'food_remark' => $description,
                                    'food_ext' => $weight ?? 0,
                                    'food_sub' => $this->prepareSub($description),
                                    'food_price' => $price,
                                    'food_discount_price' => $tmp_old_price,
                                    'food_status' => is_null($food_status_index) ? FoodStatusEnum::Unset : $food_status[$food_status_index],
                                    'rest_id' => $rest->id,
                                    'food_category_id' => $category->id,
                                    'food_img' => $item2["thumb_photo"],
                                    'stop_list' => false,
                                ]);

                                if (!is_null($food_status_index))
                                    if ($food_status[$food_status_index] === FoodStatusEnum::Promotion) {
                                        $promotion = Promotion::where('product->food_name', $product->food_name)
                                            ->where('product->rest_id', $product->rest_id)
                                            ->first();

                                        if (is_null($promotion))
                                            Promotion::create([
                                                'product' => $product
                                            ]);
                                    }


                                if (is_null($rest->categories()->find($category->id)))
                                    RestoranInCategory::create([
                                        'category_id' => $category->id,
                                        'restoran_id' => $rest->id
                                    ]);


                                $rate = Rating::create([
                                    'content_type' => \App\Enums\ContentTypeEnum::Menu,
                                    'content_id' => $product->id,
                                ]);

                                $product->rating_id = $rate->id;
                                $product->save();
                            }


                            sleep(2);

                        }
                    }
                    //dd($response["items"]);

                */

    }

    public function callback(Request $request)
    {
        ini_set('max_execution_time', '30000');
        ini_set('memory_limit', '-1');
        if (!isset($request["code"]))
            return response()->noContent(400);

        $code = $request->code ?? null;
        $state = $request->state ?? null; //bot domain

        if (is_null($code) || is_null($state))
            return response()->noContent(404);

        $bot = Bot::query()
            ->with(["frontPad"])
            ->where("bot_domain", $state)
            ->first();

        //  $shopMode = $bot->shop_mode ?? 0;

        if (is_null($bot))
            return response()->noContent(404);

        if (is_null($bot->vk_shop_link))
            return response()->noContent(404);

        //    if ($shopMode == 0)
        return $this->shopMode($bot, $code);
        /*
                if ($shopMode == 1)
                    return $this->marketplaceMode($bot, $code);*/


        // dd($response);
    }
}
