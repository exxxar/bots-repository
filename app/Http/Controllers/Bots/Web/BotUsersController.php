<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusCategoryCollection;
use App\Http\Resources\ActionStatusCollection;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Models\ActionStatus;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BotUsersController extends Controller
{

    public function loadFriendList(Request $request): BotUserCollection
    {
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->friends($request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'));
    }

    public function loadBotUsers(Request $request): BotUserCollection
    {
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->list($request->search ?? null,
                $request->get("size") ?? config('app.results_per_page'),
                ($request->need_admins ?? false) == "true");
    }

    public function loadActionStatusHistories(Request $request): ActionStatusCollection
    {
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->actionStatusHistoryList(
                $request->event ?? 'event',
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function getUserProfilePhotos(Request $request){
        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->getUserProfilePhotos();
    }

    /**
     * @throws ValidationException
     */
    public function updateProfile(Request $request): BotUserResource
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "email" => "",
            "birthday" => "",
            "city" => "",
            "country" => "",
            "address" => "",
            "sex" => "",
        ]);

        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->updateProfile( $request->all());
    }

    /**
     * @throws ValidationException
     */
    public function updateBotUser(Request $request): BotUserResource
    {
        $request->validate([
            "id" => "required",
            "is_vip" => "required",
            "is_admin" => "required",
            "is_work" => "required",
            "user_in_location" => "required",
            "name" => "required",
            "phone" => "required",
            "email" => "",
            "birthday" => "",
            "city" => "",
            "country" => "",
            "address" => "",
            "sex" => "",
        ]);

        return BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->update( $request->all());
    }
}
