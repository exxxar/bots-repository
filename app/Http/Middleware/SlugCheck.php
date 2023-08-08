<?php

namespace App\Http\Middleware;

use App\Models\BotMenuSlug;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SlugCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $scriptId = $request->slug_id ?? $request->script_id ?? null;

        //$isDebug = env("APP_DEBUG");

        if (is_null($scriptId))
            return \response()->json(["error" => "slug id not set"], 400);

        if ($scriptId == "route")
            return $next($request);

        $slug = BotMenuSlug::query()
            ->where("id", $scriptId)
            ->first();

        if (is_null($slug))
            return \response()->json(["error" => "slug not found"], 404);

        $request->slug = $slug;

        return $next($request);
    }
}
