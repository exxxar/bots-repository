<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoryStoreRequest;
use App\Http\Requests\StoryUpdateRequest;
use App\Http\Resources\StoryCollection;
use App\Http\Resources\StoryResource;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoryController extends Controller
{
    public function index(Request $request): StoryCollection
    {
        $bot = $request->bot ?? null;

        return BusinessLogic::story()
            ->setBot($bot)
            ->list(
                size: $request->size ?? 20,
            );
    }


    public function store(Request $request): StoryResource
    {
        $request->validate([
            "title" => "required|string",
            //"type" => "required|in:image,video",
        ]);

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;

        return BusinessLogic::story()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->store($request->all());
    }

    public function destroy(Request $request, $storyId): StoryResource
    {
        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;

        return BusinessLogic::story()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->destroy($storyId);
    }
}
