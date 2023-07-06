<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmoCrmStoreRequest;
use App\Http\Requests\AmoCrmUpdateRequest;
use App\Http\Resources\AmoCrmCollection;
use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AmoCrmController extends Controller
{
    public function index(Request $request): Response
    {
        $amoCrms = AmoCrm::all();

        return new AmoCrmCollection($amoCrms);
    }

    public function store(AmoCrmStoreRequest $request): Response
    {
        $amoCrm = AmoCrm::create($request->validated());

        return new AmoCrmResource($amoCrm);
    }

    public function show(Request $request, AmoCrm $amoCrm): Response
    {
        return new AmoCrmResource($amoCrm);
    }

    public function update(AmoCrmUpdateRequest $request, AmoCrm $amoCrm): Response
    {
        $amoCrm->update($request->validated());

        return new AmoCrmResource($amoCrm);
    }

    public function destroy(Request $request, AmoCrm $amoCrm): Response
    {
        $amoCrm->delete();

        return response()->noContent();
    }
}
