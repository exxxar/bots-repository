<?php

use App\Http\Controllers\Landing\BotController;
use Illuminate\Support\Facades\Route;

Route::prefix("landing")
    ->group(function () {
        Route::post("/send-to-channel", [BotController::class, "sendToChannel"]);

        Route::prefix("bots")
            ->controller(BotController::class)
            ->group(function () {
                Route::post("simple-bot-list", "simpleList");
            });
    });
