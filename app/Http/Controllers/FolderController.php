<?php

namespace App\Http\Controllers;

use App\Http\Requests\FolderStoreRequest;
use App\Http\Requests\FolderUpdateRequest;
use App\Http\Resources\FolderCollection;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FolderController extends Controller
{
    public function index(Request $request): Response
    {
        $folders = Folder::all();

        return new FolderCollection($folders);
    }

    public function store(FolderStoreRequest $request): Response
    {
        $folder = Folder::create($request->validated());

        return new FolderResource($folder);
    }

    public function show(Request $request, Folder $folder): Response
    {
        return new FolderResource($folder);
    }

    public function update(FolderUpdateRequest $request, Folder $folder): Response
    {
        $folder->update($request->validated());

        return new FolderResource($folder);
    }

    public function destroy(Request $request, Folder $folder): Response
    {
        $folder->delete();

        return response()->noContent();
    }
}
