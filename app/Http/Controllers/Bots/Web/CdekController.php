<?php

namespace App\Http\Controllers\Bots\Web;


use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\CdekStoreRequest;
use App\Http\Requests\CdekUpdateRequest;
use App\Http\Resources\CdekCollection;
use App\Http\Resources\CdekResource;
use App\Models\Cdek;
use CdekSDK2\Exceptions\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CdekController extends Controller
{


    /**
     * @throws RequestException
     */
    public function makeOrder(Request $request)
    {
        $request->validate([
            "from" => "required",
            "to" => "required",
            "packages" => "required",
            "packages.*.weight" => "required|number",
            "packages.*.length" => "required|number",
            "packages.*.height" => "required|number",
            "packages.*.width" => "required|number",
        ]);


        return BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->createOrder(
                $request->all()
            );


    }
    /**
     * @throws RequestException
     */
    public function calcTariff(Request $request)
    {
        $request->validate([
            "from" => "required",
            "to" => "required",
            "packages" => "required",
            "packages.*.weight" => "required|number",
            "packages.*.length" => "required|number",
            "packages.*.height" => "required|number",
            "packages.*.width" => "required|number",
        ]);


        return BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->calcTariff(
                $request->all()
            );


    }

    /**
     * @throws RequestException
     */
    public function calcTariffByCode(Request $request, $tariffCode)
    {
        $request->validate([
            "from" => "required",
            "to" => "required",
            "packages" => "required",
            "packages.*.weight" => "required|number",
            "packages.*.length" => "required|number",
            "packages.*.height" => "required|number",
            "packages.*.width" => "required|number",
        ]);
        $data = BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->calcTariffByCode(
                $tariffCode,
                $request->all()
            );

        return response()->json([
            "services" => $data->services,
            "calendar_min" => $data->calendar_min,
            "calendar_max" => $data->calendar_max,
            "currency" => $data->currency,
            "delivery_sum" => $data->delivery_sum,
            "period_min" => $data->period_min,
            "period_max" => $data->period_max,
            "total_sum" => $data->total_sum,
            "weight_calc" => $data->weight_calc,
        ]);
    }


    /**
     * @throws RequestException
     * @throws ValidationException
     */
    public function getOffices(Request $request)
    {
        $request->validate([
            "city_code" => "required",
            "page" => "required",
            "size" => "required"
        ]);
        return BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->getOffices(
                $request->city_code ?? 1,
                $request->country_code ?? 'ru',
                $request->page ?? 0,
                $request->size ?? 100
            );
    }


    /**
     * @throws RequestException
     * @throws ValidationException
     */
    public function getRegions(Request $request)
    {
        return BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->getRegions(
                $request->country_code ?? 'ru',
                $request->size ?? 2
            );
    }

    /**
     * @throws RequestException
     * @throws ValidationException
     */
    public function getCities(Request $request)
    {
        $request->validate([
            "region_code"=>"required"
        ]);

        return BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->getCities(
                $request->country_code ?? 'ru',
                    $request->region_code ?? null,
                $request->city ?? null
            );
    }


    public function index(Request $request): Response
    {
        $cdeks = Cdek::all();

        return new CdekCollection($cdeks);
    }

    public function store(Request $request)
    {

        return BusinessLogic::cdek()
            ->setBot($request->bot ?? null)
            ->store($request->all());

        return \response()->noContent();
    }

    public function show(Request $request, Cdek $cdek): Response
    {
        return new CdekResource($cdek);
    }

    public function update(CdekUpdateRequest $request, Cdek $cdek): Response
    {
        $cdek->update($request->validated());

        return new CdekResource($cdek);
    }

    public function destroy(Request $request, Cdek $cdek): Response
    {
        $cdek->delete();

        return response()->noContent();
    }
}
