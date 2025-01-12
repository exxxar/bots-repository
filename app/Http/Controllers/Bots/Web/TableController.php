<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\AmoCrm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TableController extends Controller
{


    /**
     * @throws ValidationException
     */
    public function tablePay(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "table_id" => "required",
        ]);

        return response()
            ->json([
                "url" => BusinessLogic::table()
                    ->setBot($request->bot ?? null)
                    ->setBotUser($request->botUser ?? null)
                    ->setSlug($request->slug ?? null)
                    ->tablePay(
                        $request->all()
                    )
            ]);
    }

    public function sendOrderToMyChat(Request $request)
    {
        $request->validate([
            "table_id" => "required"
        ]);

        BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->sendOrderToChat(
                $request->table_id ?? null,
            );

        return response()->noContent();
    }

    public function storeAdditionalService(Request $request)
    {
        $request->validate([
            "services" => "required",
            "table_id" => "required"
        ]);

        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->storeAdditionalService(
                $request->table_id ?? null,
                $request->services ?? []
            );
    }

    public function changeBasketStatus(Request $request)
    {
        $request->validate([
            "table_id" => "required",
            "type" => "required"
        ]);

        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->changeBasketStatus(
                $request->table_id ?? null,
                $request->type ?? 0
            );
    }

    public function changeTableWaiter(Request $request)
    {
        $request->validate([
            "table_id" => "required"
        ]);

        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->changeTableWaiter($request->table_id ?? null);
    }

    public function loadTableData(Request $request): object
    {
        $request->validate([
            "table_id" => "required"
        ]);

        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->getFullTableData($request->table_id ?? null);
    }

    public function currentTable(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->current();
    }

    public function approvedSelfBasket(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->approvedSelfBasket();
    }

    public function waiterTableList(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->waiterTableList($request->size ?? null);
    }

    public function getAllTableOrders(Request $request)
    {

    }

    public function requestApproveTable(Request $request)
    {
        $request->validate([
            "table_id" => "required",
        ]);

        BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->requestApproveTable($request->table_id ?? null);

        return response()->noContent();
    }

    public function callWaiter(Request $request)
    {
        $request->validate([
            "table_id" => "required",
        ]);

        BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->callWaiter($request->table_id ?? null, $request->need_payment ?? false);

        return response()->noContent();
    }

    public function closeTable(Request $request)
    {
        $request->validate([
            "table_id" => "required",
        ]);

        BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->closeTable($request->table_id ?? null);

        return response()->noContent();
    }
}
