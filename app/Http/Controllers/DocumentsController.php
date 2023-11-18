<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentsStoreRequest;
use App\Http\Requests\DocumentsUpdateRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocumentsController extends Controller
{
    public function index(Request $request): Response
    {
        $documents = Document::all();

        return new DocumentCollection($documents);
    }

    public function store(DocumentsStoreRequest $request): Response
    {
        $document = Document::create($request->validated());

        return new DocumentResource($document);
    }

    public function show(Request $request, Document $document): Response
    {
        return new DocumentResource($document);
    }

    public function update(DocumentsUpdateRequest $request, Document $document): Response
    {
        $document->update($request->validated());

        return new DocumentResource($document);
    }

    public function destroy(Request $request, Document $document): Response
    {
        $document->delete();

        return response()->noContent();
    }
}
