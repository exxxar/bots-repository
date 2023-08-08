<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     //   Log::info("test".$domain);
        BotManager::bot()->handler($domain);
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
