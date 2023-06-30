<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotMenuSlugStoreRequest;
use App\Http\Requests\BotMenuSlugUpdateRequest;
use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Models\BotMenuSlug;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotMenuSlugController extends Controller
{
    public function index(Request $request)
    {
        $needGlobal = $request->needGlobal ?? false;
        $search = $request->search ?? null;

        $botId = $request->bot_id ?? null;

        $size = $request->get("size") ?? config('app.results_per_page');

        if ($needGlobal) {

            $botMenuSlugs = BotMenuSlug::query()
                ->where("is_global", true)
                ->whereNull("bot_id");

            if (!is_null($search))
                $botMenuSlugs = $botMenuSlugs
                    ->where(function ($q) use ($search) {
                        $q->where("command", "like", "%$search%")
                            ->orWhere("comment", "like", "%$search%");
                    });

            $botMenuSlugs = $botMenuSlugs
                ->orderBy("created_at","desc")
                ->paginate($size);

            return new BotMenuSlugCollection($botMenuSlugs);
        }

        $botMenuSlugs = BotMenuSlug::query()
            ->where("bot_id", $botId);

        if (!is_null($search))
            $botMenuSlugs = $botMenuSlugs
                ->where(function ($q) use ($search) {
                    $q->where("command", "like", "%$search%")
                        ->orWhere("comment", "like", "%$search%");
                });

        $botMenuSlugs = $botMenuSlugs
            ->orderBy("created_at","desc")
            ->paginate($size);


        return new BotMenuSlugCollection($botMenuSlugs);
    }


    public function destroy(Request $request, $slugId): Response
    {

        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            return response()->noContent(404);

        $botMenuSlug->deleted_at = Carbon::now();
        $botMenuSlug->save();

        return response()->noContent();
    }

    public function duplicate(Request $request, $slugId)
    {
        $botMenuSlug = BotMenuSlug::query()->find($slugId);
        if (is_null($botMenuSlug))
            return response()->noContent();

        $botMenuSlug = $botMenuSlug->replicate();
        $botMenuSlug->save();

        return response()->noContent();
    }


    public function createSlug(Request $request)
    {
        $request->validate([
            "command" => "required",
            "slug" => "required",
        ]);

        $tmp = (object)$request->all();

        $tmp->config = json_decode($tmp->config ?? '[]');
        $tmp->is_global = $tmp->is_global == "true";

        //      dd($tmp);
        BotMenuSlug::query()->create((array)$tmp);

        return response()->noContent();
    }

    public function updateSlug(Request $request)
    {

        $request->validate([
            "id" => "required",
        ]);

        $slug = BotMenuSlug::query()->find($request->id);
        if (is_null($slug))
            return response()->noContent(404);

        $tmp = (object)$request->all();
        $tmp->is_global = $tmp->is_global ?? false;
        $tmp->config = json_decode($tmp->config);

        $slug->update((array)$tmp);

        return response()->noContent();
    }
}
