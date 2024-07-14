<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Bot;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function getDeliveryPrice(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "city" => "required",
            "street" => "required",
            "building" => "required",
        ]);

        $slug = $request->slug ?? null;

        if (is_null($slug))
            return response()->json([
                "distance" => 0,
                "price" => 0
            ]);

        $price_per_km = (Collection::make($slug->config)
            ->where("key", "price_per_km ")
            ->first())["value"] ?? 80;

        $min_base_delivery_price = (Collection::make($slug->config)
            ->where("key", "min_base_delivery_price ")
            ->first())["value"] ?? 100;


        $geo = BusinessLogic::geo()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->getCoords([
                "address" => (($request->city ?? "") . "," . ($request->street ?? "") . "," . ($request->building ?? ""))
            ]);

        $distance = BusinessLogic::geo()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->getDistance($geo["lat"], $geo["lon"]);

        return response()->json([
            "distance" => $distance ?? 0,
            "price" => $min_base_delivery_price + ($distance ?? 0) * $price_per_km
        ]);
    }

    public function getOrders(Request $request): \App\Http\Resources\OrderCollection
    {
        return BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->orderList($request->get("size") ?? config('app.results_per_page'));

    }

    public function repeatOrder(Request $request): ProductCollection
    {
        $request->validate([
            "products" => "required"
        ]);

        return BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->repeatOrder($request->all());
    }

    public function getProductsByIds(Request $request): ProductCollection
    {
        $request->validate([
            "ids" => "required|array"
        ]);

        return BusinessLogic::products()
            ->byIds($request->ids ?? []);
    }


    public function removeCategoryId(Request $request, $categoryId): ProductCategoryResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->destroyCategory($categoryId);
    }


    public function listByCategories(Request $request): ProductCategoryCollection
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->listByCategories();
    }

    public function index(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->list(
                $request->search ?? null,
                [
                    "categories" => $request->categories ?? null,
                    "min_price" => $request->min_price ?? null,
                    "max_price" => $request->max_price ?? null
                ],

                $request->get("size") ?? config('app.results_per_page')
            );
    }

    /**
     * @throws ValidationException
     */
    public function addCategory(Request $request): ProductCategoryResource
    {
        $request->validate([
            "category" => "required"
        ]);

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->createOrUpdateCategory($request->all());
    }

    public function getCategories(Request $request): ProductCategoryCollection
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->categories();
    }

    public function getProduct(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->product($productId);
    }

    public function randomProducts(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->randomList();
    }

    /**
     * @throws ValidationException
     */
    public function saveProduct(Request $request): ProductResource
    {
        $request->validate([
            "article" => "",
            "title" => "required",
            "description" => "required",
            "type" => "required",
            "current_price" => "required",
        ]);


        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->createOrUpdate($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);

    }

    public function removeAllProducts(Request $request)
    {
        BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->removeAllProducts();
    }

    public function destroy(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->destroy($productId);
    }

    public function duplicate(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->duplicate($productId);
    }


    /**
     * @throws ValidationException
     */
    public function createCheckoutLink(Request $request)
    {
        $request->validate([
            "products" => "required",
            "name" => "required",
            "phone" => "required",
        ]);

        return BusinessLogic::products()
            ->setSlug($request->slug ?? null)
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->createCheckoutLink($request->all());

    }

    /**
     * @throws ValidationException
     */
    public function checkoutInstruction(Request $request)
    {
        $request->validate([
            "products" => "required",
            "name" => "required",
            "phone" => "required",
        ]);

        BusinessLogic::products()
            ->setSlug($request->slug ?? null)
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->checkoutInformation($request->all(),
                $request->hasFile('photo') ? $request->file('photo') : null
            );

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function checkout(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "ids" => "required|array"
        ]);
        BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->checkout($request->all());

        return response()->noContent();
    }


    public function getProductsInCategory(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->productsInCategory(
                $request->category_id ?? null,
                $request->search ?? null
            );
    }

    public function getCategory(Request $request, $categoryId): ProductCategoryResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->category($categoryId);
    }


}
