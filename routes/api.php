<?php

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

Route::apiResource('company', App\Http\Controllers\CompanyController::class);

Route::apiResource('location', App\Http\Controllers\LocationController::class);

Route::apiResource('bot', App\Http\Controllers\BotController::class);

Route::apiResource('bot-menu-template', App\Http\Controllers\BotMenuTemplateController::class);

Route::apiResource('bot-type', App\Http\Controllers\BotTypeController::class);

Route::apiResource('image-menu', App\Http\Controllers\ImageMenuController::class);

Route::apiResource('cash-back', App\Http\Controllers\CashBackController::class);

Route::apiResource('cash-back-history', App\Http\Controllers\CashBackHistoryController::class);

Route::apiResource('user', App\Http\Controllers\UserController::class);

Route::apiResource('bot-user', App\Http\Controllers\BotUserController::class);

Route::apiResource('role', App\Http\Controllers\RoleController::class);

Route::apiResource('referral-history', App\Http\Controllers\ReferralHistoryController::class);

Route::apiResource('product', App\Http\Controllers\ProductController::class);

Route::apiResource('product-category', App\Http\Controllers\ProductCategoryController::class);

Route::apiResource('basket', App\Http\Controllers\BasketController::class);

Route::apiResource('order', App\Http\Controllers\OrderController::class);

Route::apiResource('event', App\Http\Controllers\EventController::class);

Route::apiResource('notification-schedule', App\Http\Controllers\NotificationScheduleController::class);


Route::apiResource('bot-menu-slug', App\Http\Controllers\BotMenuSlugController::class);
