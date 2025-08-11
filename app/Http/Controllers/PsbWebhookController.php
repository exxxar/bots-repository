<?php

namespace App\Http\Controllers;

use App\Http\BusinessLogic\Methods\Classes\Banking\PSBBankService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PsbWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $psb = new PSBBankService();
        $encrypted = $request->getContent();
        $data = $psb->decryptCallback($encrypted);

        // Здесь ваша логика обработки платежей
        // Например:
        foreach ($data['payments'] ?? [] as $payment) {
            // $payment['orderId'], $payment['state'], ...
        }

        return response()->json([
            'payments' => array_map(fn($p) => [
                'orderId' => $p['orderId'],
                'action'  => 'CONFIRM', // или REJECT
            ], $data['payments'] ?? [])
        ], Response::HTTP_OK);
    }
}
