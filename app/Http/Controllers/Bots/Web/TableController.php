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
            ->getFullTableData($request->table_id ?? null);
    }

    public function currentTable(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->current($request->table_id ?? null);
    }

    public function approvedSelfBasket(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->approvedSelfBasket();
    }

    public function waiterTableList(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
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
            ->closeTable($request->table_id ?? null);

        return response()->noContent();
    }

    public function nearestBookingList(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->nearestBookingList($request->all());
    }

    public function myUpcomingBookings(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->myUpcomingBookings();
    }

    public function bookingList(Request $request)
    {
        $request->validate([
            "number" => "required"
        ]);

        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->bookingList($request->number ?? null, $request->date ?? null);
    }

    public function bookATable(Request $request)
    {
        return BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->bookATable($request->all());
    }

    public function exportNearestBookings(Request $request)
    {
         BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->exportNearestBookings($request->all());

         return response()->noContent();
    }

    /**
     * @throws \HttpException
     * @throws ValidationException
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        BusinessLogic::table()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->cancelBookingTable($bookingId);

        return response()->noContent();
    }
}
