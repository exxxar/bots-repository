<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrafficSourceStoreRequest;
use App\Http\Requests\TrafficSourceUpdateRequest;
use App\Http\Resources\TrafficSourceCollection;
use App\Http\Resources\TrafficSourceResource;
use App\Models\TrafficSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ClientErrorController extends Controller
{


    public function store(Request $request)
    {
        // Валидация входных данных
        $data = $request->validate([
            'message' => 'nullable|string',
            'source' => 'nullable|string',
            'lineno' => 'nullable|integer',
            'colno' => 'nullable|integer',
            'stack' => 'nullable|string',
            'type' => 'nullable|string',
            'reason' => 'nullable|string',
            'src' => 'nullable|string',
        ]);

        Log::error('Client error', $data);

        return response()->json(['status' => 'ok']);
    }

}
