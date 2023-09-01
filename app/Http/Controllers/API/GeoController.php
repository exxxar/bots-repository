<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeoStoreRequest;
use App\Http\Requests\GeoUpdateRequest;
use App\Http\Resources\GeoCollection;
use App\Http\Resources\GeoResource;
use App\Models\Geo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GeoController extends Controller
{
    public function index(Request $request): Response
    {
        $geos = Geo::all();

        return new GeoCollection($geos);
    }

    public function store(GeoStoreRequest $request): Response
    {
        $geo = Geo::create($request->validated());

        return new GeoResource($geo);
    }

    public function show(Request $request, Geo $geo): Response
    {
        return new GeoResource($geo);
    }

    public function update(GeoUpdateRequest $request, Geo $geo): Response
    {
        $geo->update($request->validated());

        return new GeoResource($geo);
    }

    public function destroy(Request $request, Geo $geo): Response
    {
        $geo->delete();

        return response()->noContent();
    }
}
