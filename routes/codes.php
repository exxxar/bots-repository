<?php

use App\Facades\StartCodesService;

StartCodesService::bot()
    ->controller(\App\Http\Controllers\StartCodesHandlerController::class)
    ->regular("/([0-9]{3})U([0-9]{10})B([0-9]{10})A([0-9]{10})/", "paymentAction")
    ->regular("/([0-9]{3})([0-9]{8,10})S([0-9]+)/", "slugAction")
    ->regular("/([0-9]{3})([0-9]{8,10})O([0-9]+)/", "orderAction")
    ->regular("/([0-9]{3})([0-9]+)/", "referralAction")
    ->regular("/([0-9]{3})([0-9]+)utm([a-zA-Z0-9]+)/", "runPageAction");




