<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrafficSourceStoreRequest;
use App\Http\Requests\TrafficSourceUpdateRequest;
use App\Http\Resources\TrafficSourceCollection;
use App\Http\Resources\TrafficSourceResource;
use App\Models\TrafficSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrafficSourceController extends Controller
{
    public function index(Request $request): Response
    {
        $trafficSources = TrafficSource::all();

        return new TrafficSourceCollection($trafficSources);
    }

    public function store(TrafficSourceStoreRequest $request): Response
    {
        $trafficSource = TrafficSource::create($request->validated());

        return new TrafficSourceResource($trafficSource);
    }

    public function show(Request $request, TrafficSource $trafficSource): Response
    {
        return new TrafficSourceResource($trafficSource);
    }

    public function update(TrafficSourceUpdateRequest $request, TrafficSource $trafficSource): Response
    {
        $trafficSource->update($request->validated());

        return new TrafficSourceResource($trafficSource);
    }

    public function destroy(Request $request, TrafficSource $trafficSource): Response
    {
        $trafficSource->delete();

        return response()->noContent();
    }
}
