<?php

namespace App\Http\Controllers;

use App\Http\Requests\TableStoreRequest;
use App\Http\Requests\TableUpdateRequest;
use App\Http\Resources\TableCollection;
use App\Http\Resources\TableResource;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TableController extends Controller
{
    public function index(Request $request): Response
    {
        $tables = Table::all();

        return new TableCollection($tables);
    }

    public function store(TableStoreRequest $request): Response
    {
        $table = Table::create($request->validated());

        return new TableResource($table);
    }

    public function show(Request $request, Table $table): Response
    {
        return new TableResource($table);
    }

    public function update(TableUpdateRequest $request, Table $table): Response
    {
        $table->update($request->validated());

        return new TableResource($table);
    }

    public function destroy(Request $request, Table $table): Response
    {
        $table->delete();

        return response()->noContent();
    }
}
