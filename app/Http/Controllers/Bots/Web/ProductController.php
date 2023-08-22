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
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function getProductsByIds(Request $request): ProductCollection
    {
        $request->validate([
            "ids" => "required|array"
        ]);

        return BusinessLogic::products()
            ->byIds($request->ids ?? []);
    }

    public function index(Request $request): ProductCollection
    {
        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();


        return BusinessLogic::products()
            ->setBot($bot)
            ->list(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function getCategories(Request $request): ProductCategoryCollection
    {
        $request->validate([
            "bot_id" => "required"
        ]);

        $bot = Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::products()
            ->setBot($bot)
            ->categories();
    }

    public function getProduct(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->product($productId);
    }

    public function randomProducts(Request $request): ProductCollection
    {
        $request->validate([
            "bot_id" => "required"
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();


        return BusinessLogic::products()
            ->setBot($bot)
            ->randomList();
    }

    /**
     * @throws ValidationException
     */
    public function saveProduct(Request $request): ProductResource
    {
        $request->validate([
            "article" => "required",
            "title" => "required",
            "description" => "required",
            "type" => "required",
            "current_price" => "required",
        ]);


        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->setBotUser($request->botUser ?? null)
            ->createOrUpdate($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);

    }

    public function destroy(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->setBotUser($request->botUser ?? null)
            ->destroy($productId);
    }

    public function duplicate(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->setBotUser($request->botUser ?? null)
            ->duplicate($productId);
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
            ->setSlug($request->slug ?? null)
            ->setBotUser($request->botUser ?? null)
            ->checkout($request->all());

        return response()->noContent();
    }


    public function getProductsInCategory(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->setBotUser($request->botUser ?? null)
            ->productsInCategory(
                $request->category_id ?? null,
                $request->search ?? null
            );
    }

    public function getCategory(Request $request, $categoryId): ProductCategoryResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->setBotUser($request->botUser ?? null)
            ->category($categoryId);
    }


}
