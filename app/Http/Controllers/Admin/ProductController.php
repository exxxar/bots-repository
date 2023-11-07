<?php

namespace App\Http\Controllers\Admin;


use App\Facades\BusinessLogic;
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
                null,
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
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::products()
            ->setBot($bot)
            ->createOrUpdate($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);

    }

    public function destroy(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->destroy($productId);
    }

    public function duplicate(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->duplicate($productId);
    }

}
