<?php

namespace App\Http\Controllers;

use App\Http\Requests\CdekStoreRequest;
use App\Http\Requests\CdekUpdateRequest;
use App\Http\Resources\CdekCollection;
use App\Http\Resources\CdekResource;
use App\Models\Cdek;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CdekController extends Controller
{
    public function index(Request $request): Response
    {
        $cdeks = Cdek::all();

        return new CdekCollection($cdeks);
    }

    public function store(CdekStoreRequest $request): Response
    {
        $cdek = Cdek::create($request->validated());

        return new CdekResource($cdek);
    }

    public function show(Request $request, Cdek $cdek): Response
    {
        return new CdekResource($cdek);
    }

    public function update(CdekUpdateRequest $request, Cdek $cdek): Response
    {
        $cdek->update($request->validated());

        return new CdekResource($cdek);
    }

    public function destroy(Request $request, Cdek $cdek): Response
    {
        $cdek->delete();

        return response()->noContent();
    }
}
