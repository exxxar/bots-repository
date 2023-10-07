<?php

namespace App\Http\Controllers;

use App\Facades\BusinessLogic;
use App\Http\Requests\ManagerProfileStoreRequest;
use App\Http\Requests\ManagerProfileUpdateRequest;
use App\Http\Resources\ManagerProfileCollection;
use App\Http\Resources\ManagerProfileResource;
use App\Models\ManagerProfile;
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
            "address" => "required",
            "sex" => "required",
            "referral" => "",
            "info" => "",
            "strengths" => "required",
            "weaknesses" => "required",
            "educations" => "required",
            "social_links" => "required",
            "skills" => "required",
        ]);

        BusinessLogic::manager()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->managerRegister($request->all(),
                $request->hasFile('image') ? $request->file('photo') : null
            );

        return response()->noContent();
    }
}
