<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentHistoryStoreRequest;
use App\Http\Requests\PaymentHistoryUpdateRequest;
use App\Http\Resources\PaymentHistoryCollection;
use App\Http\Resources\PaymentHistoryResource;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentHistoryController extends Controller
{
    public function index(Request $request): Response
    {
        $paymentHistories = PaymentHistory::all();

        return new PaymentHistoryCollection($paymentHistories);
    }

    public function store(PaymentHistoryStoreRequest $request): Response
    {
        $paymentHistory = PaymentHistory::create($request->validated());

        return new PaymentHistoryResource($paymentHistory);
    }

    public function show(Request $request, PaymentHistory $paymentHistory): Response
    {
        return new PaymentHistoryResource($paymentHistory);
    }

    public function update(PaymentHistoryUpdateRequest $request, PaymentHistory $paymentHistory): Response
    {
        $paymentHistory->update($request->validated());

        return new PaymentHistoryResource($paymentHistory);
    }

    public function destroy(Request $request, PaymentHistory $paymentHistory): Response
    {
        $paymentHistory->delete();

        return response()->noContent();
    }
}
