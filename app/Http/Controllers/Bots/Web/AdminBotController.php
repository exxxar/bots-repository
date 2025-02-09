<?php

namespace App\Http\Controllers\Bots\Web;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Exports\BotCashBackExport;
use App\Exports\BotStatisticExport;
use App\Exports\BotUsersExport;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Telegram\Bot\FileUpload\InputFile;

class AdminBotController extends Controller
{

    public function trafficStatistic(Request $request)
    {

        $request->validate([
            "date" => "required"
        ]);

        $bot = $request->bot ?? null;

        $botUser = $request->botUser ?? null;


        $traffics = BusinessLogic::stat()
            ->setBot($bot ?? null)
            ->setBotUser($botUser)
            ->traffic(
                $request->date[0] ?? null,
                $request->date[1] ?? null,
                $request->need_all ?? false,
                $request->sort ?? null);

        return response()->json([
            "traffics" => $traffics
        ]);
    }

    public function statistic(Request $request): \Illuminate\Http\JsonResponse
    {

        $sort = $request->sort;

        $statistics = BusinessLogic::stat()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->base($request->date[0] ?? null,
                $request->date[1] ?? null,
                $request->need_all ?? false,
                $sort["direction"] ?? 'asc',
                $sort['key'] ?? 'price');

        return response()->json([
            'statistic' => $statistics
        ]);
    }

    public function exportBotStatistic(Request $request): void
    {

        BusinessLogic::administrative()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->exportBotStatistic();

    }

    public function exportBotUsers(Request $request): void
    {

        BusinessLogic::botUsers()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->exportBotUsers();

    }

    public function exportCashBackHistory(Request $request): void
    {
        $orderBy = $request->orderBy ?? null;
        $direction = $request->direction ?? null;

        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->exportCashBackHistory();

    }


    public function loadActiveAdminList(Request $request): \App\Http\Resources\BotUserCollection
    {

        return BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->adminList($request->get("size") ?? config('app.results_per_page'));

    }

    protected function requestUserReview(Request $request)
    {
        $request->validate([
            "telegram_chat_id" => "required",
        ]);


        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->requestReview($request->all());

        return response()->noContent();
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
            ->setBotUser($request->botUser ?? null)
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
            "message" => "",
        ]);

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
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
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->removeCashBack($request->all());

        return response()->noContent();
    }


    /**
     * @throws ValidationException
     */
    public function sendPageToUser(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
            "page_id" => "required|integer",
        ]);

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->sendPageToUser($request->all());

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
            ->setBotUser($request->botUser ?? null)
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
            ->setBotUser($request->botUser ?? null)
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
            ->setBotUser($request->botUser ?? null)
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
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->removeAdmin($request->all());

        return response()->noContent();
    }

    public function selfRemoveAdmin(Request $request): \Illuminate\Http\Response
    {

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->selfRemoveAdmin();

        return response()->noContent();
    }

    public function workStatus(Request $request): \Illuminate\Http\Response
    {

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->workStatus();

        return response()->noContent();
    }

    public function getBotAdminMenu2()
    {
        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin) {
            BotManager::bot()->reply("Вы не являетесь администратором данного бота!");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard("Административная панель (Версия 2)", [
                [
                    ["text" => "Открыть", "web_app" => [
                        "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route&hide_menu#/s/admin/menu"//"/restaurant/active-admins/$bot->bot_domain"
                    ]],
                ],
            ]);
    }

    public function getBotAdminMenu()
    {

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin) {
            BotManager::bot()->reply("Вы не являетесь администратором данного бота!");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard("Административная панель", [
                [
                    ["text" => "Открыть", "web_app" => [
                        "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route#/admin-main"//"/restaurant/active-admins/$bot->bot_domain"
                    ]],
                ],
            ]);

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
    public function storeProfileForm(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "page_id" => "required",

        ]);

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->storeProfile($request->all());

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
            // "birthday" => "required",
            //  "city" => "required",
            //"country" => "required",
            //"address" => "required",
            "sex" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
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
    public function messageToUser(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "info" => "required",
        ]);

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->messageToUser($request->all());

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
            ->setBotUser($request->botUser ?? null)
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
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->requestRefreshMenu($request->all());

        return response()->noContent();
    }
}
