<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StartCodesHandlerController extends Controller
{
    public function paymentAction(...$data)
    {
        Log::info("paymentAction" . print_r($data, true));
    }

    public function slugAction(...$data)
    {
        Log::info("slugAction" . print_r($data, true));
    }

    public function referralAction(...$data)
    {
        Log::info("referralAction" . print_r($data, true));
    }
}
