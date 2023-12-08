<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function media(Request $request)
    {
        return BusinessLogic::media()
            ->setBot($request->bot ?? null)
            ->list([
                "video" => $request->needVideo ?? null,
                "video_note" => $request->needVideo ?? null,
                "photo" => $request->needPhoto ?? null,
                "audio" => $request->needAudio ?? null,
                "document" => $request->needDocument ?? null,
            ],
                $request->search ?? null,
                $request->size ?? null
            );
    }

    public function preview(Request $request, $mediaId): \Illuminate\Http\Response
    {
        BusinessLogic::media()
            ->setBotUser($request->botUser ?? null)
            ->preview($mediaId);

        return response()->noContent();
    }

    public function remove($mediaId): \App\Http\Resources\BotMediaResource
    {
        return BusinessLogic::media()->destroy($mediaId);
    }
}
