<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    public function index(Request $request): Response
    {
        $locations = Location::all();

        return new LocationCollection($locations);
    }

    public function store(LocationStoreRequest $request): Response
    {
        $location = Location::create($request->validated());

        return new LocationResource($location);
    }

    public function show(Request $request, Location $location): Response
    {
        return new LocationResource($location);
    }

    public function update(LocationUpdateRequest $request, Location $location): Response
    {
        $location->update($request->validated());

        return new LocationResource($location);
    }

    public function destroy(Request $request, Location $location): Response
    {
        $location->delete();

        return response()->noContent();
    }
}
