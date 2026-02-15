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
        Route::prefix("shop")
            ->group(function () {


                Route::prefix("reviews")
                    ->group(function () {
                        Route::post("/", [ProductController::class, "getReviews"]);
                        Route::post("/by-product-id", [ProductController::class, "getReviewsByProductId"]);
                        Route::post("/store-review", [ProductController::class, "storeReview"]);
                        Route::post("/notify-user", [ProductController::class, "notifyUser"]);


                    });



                Route::post("/products", [ProductController::class, "index"]);
                Route::post("/products-by-category", [ProductController::class, "listByCategories"]);
                Route::post("/products-more-by-category", [ProductController::class, "loadMoreProductsByCategories"]);
                Route::post("/products/load-data", [\App\Http\Controllers\Globals\SimpleDeliveryController::class, "loadData"]);
                Route::post("/checkout", [ProductController::class, "checkout"]);



                Route::post("/checkout-instruction", [ProductController::class, "checkoutInstruction"])
                    ->middleware(["slug"]);
                Route::post("/checkout-link", [ProductController::class, "createCheckoutLink"])
                    ->middleware(["slug"]);
                Route::post("/products/store-category", [ProductController::class, "storeCategory"]);
                Route::post("/products/fav-list", [ProductController::class, "getFavList"]);
                Route::post("/products/toggle-favorite", [ProductController::class, "toggleProductInFavorites"]);
                Route::post("/products/export-all-products", [ProductController::class, "exportAllProducts"]);
                Route::post("/products/load-recommended-products", [ProductController::class, "loadRecommendedProducts"]);
                Route::post("/products/by-ids", [ProductController::class, "getProductsByIds"]);
                Route::post("/products/random", [ProductController::class, "randomProducts"]);
                Route::post("/products/categories", [ProductController::class, "getCategories"]);
                Route::post("/products/add-product", [ProductController::class, "saveProduct"]);
                Route::post("/products/change-recommendation-status", [ProductController::class, "changeRecommendationStatus"]);
                Route::post("/products/categories/recommendation-status", [ProductController::class, "changeCategoryRecommendationStatus"]);
                Route::post("/products/remove-all-products", [ProductController::class, "removeAllProducts"]);
                Route::delete("/products/remove-category/{categoryId}", [ProductController::class, "removeCategoryId"]);
                Route::post("/products/categories/status/{id}", [ProductController::class, "changeCategoryStatus"]);
                Route::post("/products/add-category", [ProductController::class, "storeCategory"]);
                Route::post("/products/in-category", [ProductController::class, "getProductsInCategory"]);
                Route::post("/products/category/{productId}", [ProductController::class, "getCategory"]);
                Route::post("/products/{productId}", [ProductController::class, "getProduct"]);
                Route::post("/products/restore-product/{productId}", [ProductController::class, "restore"])
                    ->middleware(["tgAuth.admin"]);
                Route::post("/products/stop-list-product/{productId}", [ProductController::class, "stopList"])
                    ->middleware(["tgAuth.admin"]);

                Route::delete("/products/{productId}", [ProductController::class, "destroy"])
                    ->middleware(["tgAuth.admin"]);

            });

    });


