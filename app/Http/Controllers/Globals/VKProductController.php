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

    protected $bot = null;

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

    protected function importProducts($vkProducts, $album, &$results)
    {
        $bot = $this->bot;

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

    public function shopMode($code)
    {


        $products = Product::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        foreach ($products as $product) {
            $product->in_stop_list_at = Carbon::now();
            $product->deleted_at = Carbon::now();
            $product->save();
        }


        $this->fpProducts = !is_null($this->bot->frontPad ?? null) && !is_null($this->bot->frontPad->token ?? null) ?
            BusinessLogic::frontPad()
                ->setBot($this->bot)
                ->getProducts() : null;


        $results = (object)[
            "total_product_count" => 0,
            "created_product_count" => 0,
            "updated_product_count" => 0,
            "total_frontpad_count" => 0,
            "front_pad_not_found_items" => [],
        ];

        $input = trim($this->bot->vk_shop_link);

        $links = str_contains($input, ',') ? array_map('trim', explode(',', $input)) : [$input];
        foreach ($links as $link) {
            if (!empty($link)) {
                $this->vkHandler($link, $code, $results);
            }
        }

        Inertia::setRootView("shop");
        return Inertia::render('V2/Result', [
            'message' => "Товары успешно добавлены!",
            'data' => json_encode($results)
        ]);
    }

    protected function vkHandler($link, $code, &$results)
    {
        $oauth = new VKOAuth();
        $client_id = env("VK_CLIENT_ID");
        $client_secret = env('VK_CLIENT_SECRET');
        $redirect_uri = env("APP_URL") . '/bot-client/vk-callback';

        $tmpScreenName = substr($link, strpos($link, "https://vk.com/") + strlen("https://vk.com/"));
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


                    $this->importProducts($vkProducts, $album, $results);

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

                $this->importProducts($vkProducts, null, $results);
            }

        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getLine());
            Inertia::setRootView("shop");

            return Inertia::render('V2/Result', [
                'message' => "Ошибка добавления товаров!",
            ]);
        }

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

        $this->bot = Bot::query()
            ->with(["frontPad"])
            ->where("bot_domain", $state)
            ->first();


        if (is_null($this->bot))
            return response()->noContent(404);

        if (is_null($this->bot->vk_shop_link))
            return response()->noContent(404);

        return $this->shopMode($code);

    }
}
