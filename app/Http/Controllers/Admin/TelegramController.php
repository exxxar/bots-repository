<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMedia;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Psy\Util\Str;

class TelegramController extends Controller
{
    public function registerWebhooks(Request $request)
    {
        return response()->json(BotManager::bot()->setWebhooks());
    }

    public function handler(Request $request, $domain)
    {
        BotManager::bot()->handler($domain);

        return response()->json([
            "message" => "Ok"
        ]);
    }

    public function webInterface(Request $request, $domain)
    {
        Inertia::setRootView("landing");

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $domain)
            ->first();

        return Inertia::render('ChatWindow', [
            'bot' => $bot,
        ]);
    }

    public function webHandler(Request $request, $domain)
    {
        $request->validate([
            "message" => "nullable",
            "query" => "nullable",
            "user.id" => "required",
            "user.first_name" => "required",
            "user.last_name" => "required",
            "user.username" => "required"
        ]);


        return BotManager::bot()
            ->webHandler($domain,
                (object)$request->all());
    }

    public function getFiles($companySlug, $file)
    {
        $path = storage_path() . '/app/public/companies/' . $companySlug . "/" . $file;

        if (!file_exists($path))
            $path = public_path() . "/images/cashman.jpg";
        return response()->download($path);
    }

    public function getFilesByBotId($botId, $file)
    {

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $botId)
            ->first();

        if (is_null($bot)) {
            $path = public_path() . "/images/cashman.jpg";
            return response()->download($path);
        }

        $company = $bot->company;

        $companySlug = $company->slug ?? null;

        $path = storage_path() . '/app/public/companies/' . $companySlug . "/" . $file;

        if (!file_exists($path))
            $path = public_path() . "/images/cashman.jpg";
        return response()->download($path);
    }

    public function getStorageFile($company, $file)
    {

        $path = Storage::disk('public')->get("/companies/" . $company . "/" . $file);


        if (!file_exists($path))
            $path = public_path() . "/images/cashman.jpg";

        return (new Response($path, 200))
            ->header('Content-Type', 'image/jpeg');

    }

    public function getFileByMediaContentIdAndBotDomain(Request $request, $fileId, $botDomain)
    {


        $bot = Bot::query()
            ->withTrashed()
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot))
            return null;

        $data = Http::get("https://api.telegram.org/bot" . $bot->bot_token . "/getFile?file_id=$fileId");

        $data = $data->json();

        if (!$data["ok"])
            return null;

        $type = explode("/", $data["result"]["file_path"]);


        switch ($type[0]) {
            case "photo":
            default:
                $file = "image.jpg";
                $contentType = "image/jpeg";
                break;
            case "videos":
            case "video_notes":
                $contentType = "video/mpeg";
                $file = "video.mp4";
                break;

        }


        $data = Http::get("https://api.telegram.org/file/bot" . $bot->bot_token . "/" . $data["result"]["file_path"]);

        return response($data)->withHeaders([
            'Content-disposition' => 'attachment; filename=' . $file,
            'Access-Control-Expose-Headers' => 'Content-Disposition',
            'Content-Type' => $contentType,
        ]);
    }

    public function getFileByMediaContentId(Request $request, $fileId)
    {

        $media = BotMedia::query()
            ->where("file_id", $fileId)
            ->first();

        if (is_null($media))
            return null;

        $bot = Bot::query()
            ->withTrashed()
            ->where("id", $media->bot_id)
            ->first();

        if (is_null($bot))
            return null;

        $data = Http::get("https://api.telegram.org/bot" . $bot->bot_token . "/getFile?file_id=$fileId");

        $data = $data->json();

        if (!$data["ok"])
            return null;

        $type = explode("/", $data["result"]["file_path"]);


        switch ($type[0]) {
            case "photo":
            default:
                $file = "image.jpg";
                $contentType = "image/jpeg";
                break;
            case "videos":
            case "video_notes":
                $contentType = "video/mpeg";
                $file = "video.mp4";
                break;

        }


        $data = Http::get("https://api.telegram.org/file/bot" . $bot->bot_token . "/" . $data["result"]["file_path"]);

        return response($data)->withHeaders([
            'Content-disposition' => 'attachment; filename=' . $file,
            'Access-Control-Expose-Headers' => 'Content-Disposition',
            'Content-Type' => $contentType,
        ]);
    }

    public function getFilesByCompanyId($companyId, $file)
    {

        $company = Company::query()
            ->where("id", $companyId)
            ->first();

        if (is_null($company)) {
            $path = public_path() . "/images/cashman.jpg";
            return response()->download($path);
        }

        $companySlug = $company->slug ?? null;

        $path = storage_path() . '/app/public/companies/' . $companySlug . "/" . $file;

        if (!file_exists($path))
            $path = public_path() . "/images/cashman.jpg";
        return response()->download($path);
    }

    public function removeFile(Request $request)
    {
        $request->validate([
            "file_path" => "required"
        ]);

        Storage::disk('public')->delete($request->file_path);

        return response()->noContent();
    }


}
