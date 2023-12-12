<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function media(Request $request): \App\Http\Resources\BotMediaCollection
    {
        $request->validate([
            "botId" => "required"
        ]);

        $bot = Bot::query()->find($request->botId);

        return BusinessLogic::media()
            ->setBot($bot ?? null)
            ->list([
                "video" => $request->needVideo ?? null,
                "video_note" => $request->needVideo ?? null,
                "photo" => $request->needPhoto ?? null,
                "audio" => $request->needAudio ?? null,
                "voice" => $request->needAudio ?? null,
                "document" => $request->needDocument ?? null,
            ],
                $request->search ?? null,
                $request->size ?? null
            );
    }

    public function preview(Request $request, $mediaId): \Illuminate\Http\Response
    {
        BusinessLogic::media()->preview($mediaId);
        return response()->noContent();
    }

    public function remove($mediaId): \App\Http\Resources\BotMediaResource
    {
        return BusinessLogic::media()->destroy($mediaId);
    }
}
