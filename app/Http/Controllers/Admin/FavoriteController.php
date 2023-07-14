<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteStoreRequest;
use App\Http\Requests\FavoriteUpdateRequest;
use App\Http\Resources\FavoriteCollection;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\ProductCollection;
use App\Models\BotUser;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "tg_id" => "required",
        ]);
        $search = $request->search ?? null;

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $request->tg_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $favorites = Favorite::query()
            ->with(["product"])
            ->where("bot_id", $request->bot_id)
            ->where("user_id", $botUser->user_id);

        if (!is_null($search))
            $favorites = $favorites
                ->whereHas('product', function ($q) use ($search) {
                    $q->where("title", "like", "%$search%")
                        ->orWhere("description", "like", "%$search%");
                });

        $favorites = $favorites
            ->orderBy("created_at", "DESC")
            ->paginate(20);

        return new FavoriteCollection($favorites);
    }

    public function store(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "product_id" => "required",
            "tg_id" => "required",
        ]);

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $request->tg_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $favorite = Favorite::query()->create([
            'product_id'=>$request->product_id,
            'user_id'=>$botUser->user_id,
            'bot_id'=>$request->bot_id
        ]);

        return new FavoriteResource($favorite);
    }

    public function remove(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "product_id" => "required",
            "tg_id" => "required",
        ]);

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $request->tg_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $favorite = Favorite::query()
            ->where(  'product_id', $request->product_id)
            ->where(  'user_id', $botUser->user_id)
            ->where(  'bot_id', $request->bot_id)
            ->first();

        if (is_null($favorite))
            return response()->noContent(404);

        $favorite->delete();

        return response()->noContent();
    }
}
