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

Route::apiResource('company', \App\Http\Controllers\Admin\CompanyController::class);

Route::apiResource('location', \App\Http\Controllers\Admin\LocationController::class);

Route::apiResource('bot', \App\Http\Controllers\Admin\BotController::class);

Route::apiResource('bot-menu-template', \App\Http\Controllers\Admin\BotMenuTemplateController::class);

Route::apiResource('bot-type', \App\Http\Controllers\Admin\BotTypeController::class);

Route::apiResource('image-menu', \App\Http\Controllers\Admin\ImageMenuController::class);

Route::apiResource('cash-back', \App\Http\Controllers\Admin\CashBackController::class);

Route::apiResource('cash-back-history', \App\Http\Controllers\Admin\CashBackHistoryController::class);

Route::apiResource('user', \App\Http\Controllers\Admin\UserController::class);

Route::apiResource('bot-user', \App\Http\Controllers\Admin\BotUserController::class);

Route::apiResource('role', \App\Http\Controllers\Admin\RoleController::class);

Route::apiResource('referral-history', \App\Http\Controllers\Admin\ReferralHistoryController::class);

Route::apiResource('product', \App\Http\Controllers\Admin\ProductController::class);

Route::apiResource('product-category', \App\Http\Controllers\Admin\ProductCategoryController::class);

Route::apiResource('basket', \App\Http\Controllers\Admin\BasketController::class);

Route::apiResource('order', \App\Http\Controllers\Admin\OrderController::class);

Route::apiResource('event', \App\Http\Controllers\Admin\EventController::class);

Route::apiResource('notification-schedule', \App\Http\Controllers\Admin\NotificationScheduleController::class);


Route::apiResource('bot-menu-slug', \App\Http\Controllers\Admin\BotMenuSlugController::class);


Route::apiResource('bot-product', \App\Http\Controllers\Admin\BotProductController::class);

Route::apiResource('bot-product-category', \App\Http\Controllers\Admin\BotProductCategoryController::class);


Route::apiResource('bot-text-content', \App\Http\Controllers\Admin\BotTextContentController::class);


Route::apiResource('bot-page', \App\Http\Controllers\Admin\BotPageController::class);


Route::apiResource('bot-dialog-command', \App\Http\Controllers\Admin\BotDialogCommandController::class);

Route::apiResource('bot-dialog-result', \App\Http\Controllers\Admin\BotDialogResultController::class);


Route::apiResource('bot-dialog-group', \App\Http\Controllers\Admin\BotDialogGroupController::class);


Route::apiResource('action-status', App\Http\Controllers\ActionStatusController::class);


Route::apiResource('amo-crm', App\Http\Controllers\AmoCrmController::class);
