<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageMenuStoreRequest;
use App\Http\Requests\ImageMenuUpdateRequest;
use App\Http\Resources\ImageMenuCollection;
use App\Http\Resources\ImageMenuResource;
use App\Models\ImageMenu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageMenuController extends Controller
{
    public function index(Request $request): Response
    {
        $imageMenus = ImageMenu::all();

        return new ImageMenuCollection($imageMenus);
    }

    public function store(ImageMenuStoreRequest $request): Response
    {
        $imageMenu = ImageMenu::create($request->validated());

        return new ImageMenuResource($imageMenu);
    }

    public function show(Request $request, ImageMenu $imageMenu): Response
    {
        return new ImageMenuResource($imageMenu);
    }

    public function update(ImageMenuUpdateRequest $request, ImageMenu $imageMenu): Response
    {
        $imageMenu->update($request->validated());

        return new ImageMenuResource($imageMenu);
    }

    public function destroy(Request $request, ImageMenu $imageMenu): Response
    {
        $imageMenu->delete();

        return response()->noContent();
    }
}
