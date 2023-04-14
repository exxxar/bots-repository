<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferralHistoryStoreRequest;
use App\Http\Requests\ReferralHistoryUpdateRequest;
use App\Http\Resources\ReferralHistoryCollection;
use App\Http\Resources\ReferralHistoryResource;
use App\Models\ReferralHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReferralHistoryController extends Controller
{
    public function index(Request $request): Response
    {
        $referralHistories = ReferralHistory::all();

        return new ReferralHistoryCollection($referralHistories);
    }

    public function store(ReferralHistoryStoreRequest $request): Response
    {
        $referralHistory = ReferralHistory::create($request->validated());

        return new ReferralHistoryResource($referralHistory);
    }

    public function show(Request $request, ReferralHistory $referralHistory): Response
    {
        return new ReferralHistoryResource($referralHistory);
    }

    public function update(ReferralHistoryUpdateRequest $request, ReferralHistory $referralHistory): Response
    {
        $referralHistory->update($request->validated());

        return new ReferralHistoryResource($referralHistory);
    }

    public function destroy(Request $request, ReferralHistory $referralHistory): Response
    {
        $referralHistory->delete();

        return response()->noContent();
    }
}
