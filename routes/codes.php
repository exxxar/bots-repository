<?php

use App\Facades\StartCodesService;

StartCodesService::bot()
    ->controller(\App\Http\Controllers\StartCodesHandlerController::class)
    ->regular("/\b([0-9]{3})U([0-9]{10})B([0-9]{10})A([0-9]{10})\b/", "paymentAction")
    ->regular("/\b([0-9]{3})([0-9]{8,10})S([0-9]+)\b/", "slugAction")
    ->regular("/\b([0-9]{3})([0-9]{8,10})O([0-9]+)\b/", "orderAction")
    ->regular("/\b([0-9]{3})([0-9]+)\b/", "referralAction")
    ->regular("/\b([0-9]{3})([0-9]+)utm([a-zA-Z0-9]+)\b/", "runPageAction");




