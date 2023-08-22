<?php

namespace App\Http\Controllers\Bots\Web;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotUserResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AdminBotController extends Controller
{

    public function statistic(Request $request): \Illuminate\Http\JsonResponse
    {
        $statistics = BusinessLogic::administrative()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->statistic();

        return response()->json([
            'statistic' => $statistics
        ]);
    }


    public function loadActiveAdminList(Request $request): \App\Http\Resources\BotUserCollection
    {

        return BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->adminList($request->get("size") ?? config('app.results_per_page'));

    }

    /**
     * @throws ValidationException
     */
    public function requestCashBack(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "admin_telegram_chat_id" => "required",
        ]);


        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->requestCashBack($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function addCashBack(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "amount" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->addCashBack($request->all());

        return \response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function removeCashBack(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "amount" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->removeCashBack($request->all());

        return response()->noContent();
    }


    /**
     * @throws ValidationException
     */
    public function sendInvoice(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
            "amount" => "required|integer",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->sendInvoice($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function sendApprove(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
            "action_id" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->sendApprove($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function addAdmin(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->addAdmin($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function removeAdmin(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->removeAdmin($request->all());

        return response()->noContent();
    }

    public function selfRemoveAdmin(Request $request): \Illuminate\Http\Response
    {

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->selfRemoveAdmin();

        return response()->noContent();
    }

    public function workStatus(Request $request): \Illuminate\Http\Response
    {

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->workStatus();

        return response()->noContent();
    }

    public function getBotAdminMenu()
    {

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin) {
            BotManager::bot()->reply("Вы не являетесь администратором данного бота!");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'inline',
                    'slug' => "menu_admin_main",

                ],
                [
                    'menu' => [
                        [
                            ["text" => "Открыть", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route#/admin-main"//"/restaurant/active-admins/$bot->bot_domain"
                            ]],
                        ],
                    ],
                ]);

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard("Административная панель", $menu->menu);

    }

    public function deliverymanStore(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "tg" => "required",
            "form.name" => "required",
            "form.phone" => "required",
            //"form.email" => "required",
            "form.birthday" => "required",
            "form.city" => "required",
            //"form.country" => "required",
            //"form.address" => "required",
            "form.sex" => "required",
        ]);

        $form = $request->form;
        $form["birthday"] = Carbon::parse($form["birthday"])
            ->format("Y-m-d");

        $form["sex"] = $form["sex"] === "on" ? 1 : 0;

        $botUser = BotUser::query()
            ->where("bot_id", $request->bot_id)
            ->where("telegram_chat_id", $request->tg["id"])
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $botUser->update($form);

        $botUser->age = Carbon::now()->year - Carbon::parse($botUser->birthday)
                ->year;
        $botUser->is_vip = true;
        $botUser->is_deliveryman = true;
        $botUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendSlugKeyboard(
                $botUser->telegram_chat_id,
                "Вы стали нашим <b>Доставщиком</b>! Поздравляем!",
                "main_menu_deliveryman_1"
            );
        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function vipStore(Request $request): \Illuminate\Http\Response
    {

        $request->validate([
            "name" => "required",
            "phone" => "required",
            "birthday" => "required",
            "city" => "required",
            //"country" => "required",
            //"address" => "required",
            "sex" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->vipStore($request->all());

        return response()->noContent();

    }

    public function vipFormDeliveryman($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $bot = new \App\Http\Resources\BotResource($bot);

        Inertia::setRootView("bot");

        return Inertia::render('DeliveryManForm', [
            'bot' => json_decode($bot->toJson()),
        ]);
    }

    public function vipForm($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $bot = new \App\Http\Resources\BotResource($bot);

        Inertia::setRootView("bot");

        return Inertia::render('BotPages/VipForm', [
            'bot' => json_decode($bot->toJson()),
        ]);

    }


    /**
     * @throws ValidationException
     */
    public function acceptUserInLocation(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->acceptUserInLocation($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function requestUserData(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->requestUserData($request->all());

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function requestRefreshMenu(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser( $request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->requestRefreshMenu($request->all());

        return response()->noContent();
    }
}
