<?php

use App\Http\Controllers\API\BotController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([/*"auth:sanctum"*/])
    ->group(function () {


        Route::prefix("basket")
            ->controller(\App\Http\Controllers\Bots\Web\BasketController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/', "loadProductsInBasket");
                Route::post('/checkout', "checkout");
                Route::post('/checkout-link', "checkoutLink");
                Route::post("/use-wheel-of-fortune-prize","useWheelOfFortunePrize");
                Route::post('/increment/{id}', "incrementItem");
                Route::post('/decrement/{id}', "decrementItem");
                Route::post('/inc-product', "incProductInBasket");
                Route::post('/comment-product', "commentProductInBasket");
                Route::post('/dec-product', "decProductInBasket");
                Route::post('/inc-collection', "incCollectionInBasket");
                Route::post('/dec-collection', "decCollectionInBasket");
                Route::delete('/clear', "clearBasket");
                Route::delete('/remove/{id}', "removeBasketItem");

            });
        Route::prefix("promocodes")
            ->controller(\App\Http\Controllers\Globals\PromocodeScriptController::class)
            ->group(function () {
                Route::post("/", "list")->middleware(["tgAuth.admin"]);
                Route::post("/activate-shop-discount", "activateShopDiscount");
            });

        Route::prefix("stories")
            ->group(function () {
                Route::get("/", [App\Http\Controllers\Bots\Web\StoryController::class, "index"]); // Получить список историй
            });

        Route::prefix("shop")
            ->group(function () {
                Route::prefix("reviews")
                    ->group(function () {
                        Route::post("/", [ProductController::class, "getReviews"]);
                    });

                Route::post("/products", [ProductController::class, "index"]);
                Route::post("/products-by-category", [ProductController::class, "listByCategories"]);
                Route::post("/products-more-by-category", [ProductController::class, "loadMoreProductsByCategories"]);
                Route::post("/products/load-data", [\App\Http\Controllers\Globals\SimpleDeliveryController::class, "loadData"]);

                Route::post("/products/fav-list", [ProductController::class, "getFavList"]);
                Route::post("/products/toggle-favorite", [ProductController::class, "toggleProductInFavorites"]);
                Route::post("/products/load-recommended-products", [ProductController::class, "loadRecommendedProducts"]);

            });

    });


