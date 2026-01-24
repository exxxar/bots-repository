<?php

use App\Facades\StartCodesService;

StartCodesService::bot()
    ->controller(\App\Http\Controllers\StartCodesHandlerController::class)
    ->regular("/\b([0-9]{3})U([0-9]{10})B([0-9]{10})A([0-9]{10})\b/", "paymentAction")
    ->regular("/\b([0-9]{3})([0-9]{8,10})S([0-9]+)\b/", "slugAction")
    ->regular("/\b([0-9]{3})([0-9]{8,10})O([0-9]+)\b/", "orderAction")
    ->regular("/\b([0-9]{3})PAGE([0-9]+)\b/", "editPage")
    ->regular("/\b([0-9]{3})slug([0-9]+)table([0-9]{1,2})\b/", "openTableMenu")
    ->regular("/\b([0-9]{3})slug([0-9]+)product([0-9]+)\b/", "openProduct")
    ->regular("/\b077([0-9]+)request\b/", "requestCoffee")
    ->regular("/\b([0-9]{3})register([0-9]+)\b/", "confirmRegistrationAndLogin")
    ->regular("/\b([0-9]{3})table([0-9]+)\b/", "addTableOfficiant")
    ->regular("/\b([0-9]{3})([0-9]+)utm([_a-zA-Z0-9]+)\b/", "runPageAction")
    ->regular("/\b([0-9]{3})([0-9]+)\b/", "referralAction");




