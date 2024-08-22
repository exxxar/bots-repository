<?php

namespace App\Http\Controllers;

use App\Http\Requests\BitrixStoreRequest;
use App\Http\Requests\BitrixUpdateRequest;
use App\Http\Resources\BitrixCollection;
use App\Http\Resources\BitrixResource;
use App\Models\Bitrix;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BitrixController extends Controller
{
    public function index(Request $request): Response
    {
        $bitrixes = Bitrix::all();

        return new BitrixCollection($bitrixes);
    }

    public function store(BitrixStoreRequest $request): Response
    {
        $bitrix = Bitrix::create($request->validated());

        return new BitrixResource($bitrix);
    }

    public function show(Request $request, Bitrix $bitrix): Response
    {
        return new BitrixResource($bitrix);
    }

    public function update(BitrixUpdateRequest $request, Bitrix $bitrix): Response
    {
        $bitrix->update($request->validated());

        return new BitrixResource($bitrix);
    }

    public function destroy(Request $request, Bitrix $bitrix): Response
    {
        $bitrix->delete();

        return response()->noContent();
    }
}
