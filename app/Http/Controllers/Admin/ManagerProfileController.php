<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ManagerProfileController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function registerManager(Request $request): Response
    {

        $request->validate([
            "name" => "required",
            "phone" => "required",
            "birthday" => "required",
            "city" => "required",
            // "country" => "required",
           // "address" => "required",
            "sex" => "required",
            "referral" => "",
            "info" => "",
            "strengths" => "required",
            "weaknesses" => "required",
            "educations" => "required",
            "social_links" => "required",
            "skills" => "required",
            "bot_id" => "required",
            "bot_user_id" => "required",
        ]);

        $bot = Bot::query()->find($request->bot_id);
        $botUser = BotUser::query()->find($request->bot_user_id);


        BusinessLogic::manager()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->managerRegister($request->all(),
                $request->hasFile('images') ? $request->file('images') : null
            );

        return response()->noContent();
    }
}
