<?php


use App\Http\Controllers\API\BasketController;
use App\Http\Controllers\API\BotController;
use App\Http\Controllers\API\PartnersController;
use App\Http\Controllers\API\ProductController;

use App\Http\Controllers\API\StoryController;
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

Route::middleware(['check.bot'/*"auth:sanctum"*/])
    ->group(function () {

        Route::prefix("basket")
            ->controller(BasketController::class)
            ->group(function () {
                Route::post('/', "loadProductsInBasket");
                Route::post('/checkout', "checkout");
                Route::post('/checkout-link', "checkoutLink");
                Route::post("/use-wheel-of-fortune-prize", "useWheelOfFortunePrize");
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
                Route::post("/activate-shop-discount", "activateShopDiscount");
            });

        Route::prefix("stories")
            ->group(function () {
                Route::get("/", [StoryController::class, "index"]); // Получить список историй
            });

        Route::prefix("shop")
            ->group(function () {
                Route::post('/info', [BotController::class, "getBot"]);

                Route::prefix("products")
                    ->controller(ProductController::class)
                    ->group(function () {
                        Route::post("/", "index");
                        Route::post("/by-category", "listByCategories");
                        Route::post("/more-by-category",  "loadMoreProductsByCategories");
                        Route::post("/fav-list",  "getFavList");
                        Route::post("/toggle-favorite",  "toggleProductInFavorites");
                        Route::post("/load-recommended-products",  "loadRecommendedProducts");
                    });

                Route::prefix("reviews")
                    ->group(function () {
                        Route::post("/", [ProductController::class, "getReviews"]);
                    });
            });

        Route::prefix("orders")
            ->group(function () {
                Route::post("/", [ProductController::class, "getOrders"]);
                Route::post("/send-sbp-invoice", [ProductController::class, "sendSBPInvoice"]);
                Route::post("/repeat-order", [ProductController::class, "repeatOrder"]);
                Route::post("/decline-order", [ProductController::class, "declineOrder"]);
                Route::post("/get-order-by-id", [ProductController::class, "loadOrderById"]);
                Route::post("/get-delivery-price", [ProductController::class, "getDeliveryPrice"]);
            });

        Route::prefix("partners")
            ->controller(PartnersController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/toggle-favorite", "togglePartnersInFavorites");
                Route::post("/partners-categories", "partnersCategories");
            });

    });


