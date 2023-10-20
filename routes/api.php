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

        Route::prefix("range")
            ->group(function(){

            /*    Route::post("/range/{restId}","Fastoran\OrderController@getRange");
                Route::post("/range_with_route/{restId}","Fastoran\OrderController@getRangeWithRoute");

                Route::post("/custom_range","Fastoran\OrderController@getCustomRange");

                Route::post('/wish', 'RestController@sendWish')->name("wish");*/

            });

        Route::prefix("companies")
            ->controller(CompanyController::class)
            ->group(function () {
                Route::get("/", "index");
                Route::get("/company/{id}", "loadCompanyById")
                    ->where(["id" => "[0-9]+"]);
                Route::get("/location-list/{companyId?}", "loadLocations");
            });


        Route::prefix("shops") //bots
        ->controller(BotController::class)
            ->group(function () {
                Route::post("/", "index");
            });


        Route::prefix("products")
            ->controller(ProductController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/checkout", "checkout");
                Route::post("/by-ids", "getProductsByIds");
                Route::post("/random", "randomProducts");
                Route::post("/categories", "getCategories");
                Route::post("/in-category", "getProductsInCategory");
                Route::post("/category/{productId}", "getCategory");
                Route::post("/{productId}", "getProduct");
            });

        Route::prefix("orders")
            ->group(function () {
                //history
            });

        Route::prefix("transactions")
            ->group(function () {
                //history
            });

        Route::prefix("locations")
            ->group(function () {

            });
    });




/*
 * companies (компании)
 * shops (или bots) - по сути ээто магазины
 * categories - категории товаров
 * products - товары
 * locations - локации
 * orders - заказы
 */


Route::apiResource('basket', App\Http\Controllers\BasketController::class);


Route::apiResource('manager-profile', App\Http\Controllers\ManagerProfileController::class);


Route::apiResource('bot-external-request', App\Http\Controllers\BotExternalRequestController::class);


Route::apiResource('bot-warning', App\Http\Controllers\BotWarningController::class);
