<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\IikoStoreRequest;
use App\Http\Requests\IikoUpdateRequest;
use App\Http\Resources\IikoCollection;
use App\Http\Resources\IikoResource;
use App\Models\Iiko;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class IikoController extends Controller
{
    public function index(Request $request): IikoResource
    {
        return BusinessLogic::iiko()
            ->setBot($request->bot ?? null)
            ->get();
    }

    public function getToken(Request $request): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            "token" => BusinessLogic::iiko()
                ->setBot($request->bot ?? null)
                ->getToken($request->api_login ?? null)
        ]);
    }

    public function getOrganizations(Request $request): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            "organizations" => BusinessLogic::iiko()
                ->setBot($request->bot ?? null)
                ->organizations($request->token ?? null)
        ]);
    }

    public function getTerminals(Request $request): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            "terminals" => BusinessLogic::iiko()
                ->setBot($request->bot ?? null)
                ->terminals(
                    $request->token ?? null,
                    $request->organization_id ?? null
                )
        ]);
    }

    public function getMenu(Request $request): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            "menus" => BusinessLogic::iiko()
                ->setBot($request->bot ?? null)
                ->menus()
        ]);
    }

    public function getProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "menu_id" => "required"
        ]);

        return \response()->json([
            "products" => BusinessLogic::iiko()
                ->setBot($request->bot ?? null)
                ->products($request->menu_id ?? null)
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): IikoResource
    {
        $request->validate([
            "api_login" => "required"
        ]);

        return BusinessLogic::iiko()
            ->setBot($request->bot ?? null)
            ->store($request->all());
    }


    /**
     * @throws ValidationException
     */
    public function storeProducts(Request $request): void
    {
        $request->validate([
            "products" => "required"
        ]);

        BusinessLogic::iiko()
            ->setBot($request->bot ?? null)
            ->storeProductsAndCategories($request->all());
    }



}
