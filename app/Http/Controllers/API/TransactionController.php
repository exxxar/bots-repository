<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index(Request $request): Response
    {
        $transactions = Transaction::all();

        return new TransactionCollection($transactions);
    }

    public function store(TransactionStoreRequest $request): Response
    {
        $transaction = Transaction::create($request->validated());

        return new TransactionResource($transaction);
    }

    public function show(Request $request, Transaction $transaction): Response
    {
        return new TransactionResource($transaction);
    }

    public function update(TransactionUpdateRequest $request, Transaction $transaction): Response
    {
        $transaction->update($request->validated());

        return new TransactionResource($transaction);
    }

    public function destroy(Request $request, Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }
}
