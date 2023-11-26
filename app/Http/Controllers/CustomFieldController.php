<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFieldStoreRequest;
use App\Http\Requests\CustomFieldUpdateRequest;
use App\Http\Resources\CustomFieldCollection;
use App\Http\Resources\CustomFieldResource;
use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomFieldController extends Controller
{
    public function index(Request $request): Response
    {
        $customFields = CustomField::all();

        return new CustomFieldCollection($customFields);
    }

    public function store(CustomFieldStoreRequest $request): Response
    {
        $customField = CustomField::create($request->validated());

        return new CustomFieldResource($customField);
    }

    public function show(Request $request, CustomField $customField): Response
    {
        return new CustomFieldResource($customField);
    }

    public function update(CustomFieldUpdateRequest $request, CustomField $customField): Response
    {
        $customField->update($request->validated());

        return new CustomFieldResource($customField);
    }

    public function destroy(Request $request, CustomField $customField): Response
    {
        $customField->delete();

        return response()->noContent();
    }
}
