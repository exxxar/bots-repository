<?php

namespace App\Listeners;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotMethods;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Api;

class CashBackListener
{


    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CashBackEvent $event): void
    {

        if (is_null($event))
            return;

        $bot = Bot::query()
            ->where("id", $event->botId)
            ->first();

        $botUserAdmin = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $event->botId)
            ->where("user_id", $event->adminId)
            ->first();

        $botUserUser = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $event->botId)
            ->where("user_id", $event->userId)
            ->first();



        if (is_null($botUserUser) || is_null($botUserAdmin))
            return;

        $botUserUser->user_in_location = false;
        $botUserUser->location_comment = null;
        $botUserUser->save();

        if (!$botUserAdmin->is_admin) {
            BotMethods::bot()
                ->whereId($event->botId)
                ->sendMessage(
                    $botUserAdmin->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!",
                );
            return;
        }

        $cashBack = $this->prepareUserCashBack($event->userId, $event->botId);

        if ($event->directionEnum == CashBackDirectionEnum::Crediting) {

            $levels[] = $bot->level_1 ?? env("BASE_CASHBACK_LEVEL_1") ?? 0;
            $levels[] = $bot->level_2 ?? env("BASE_CASHBACK_LEVEL_2") ?? 0;
            $levels[] = $bot->level_3 ?? env("BASE_CASHBACK_LEVEL_3") ?? 0;

            $nextUser = $botUserUser->user;
            $admin = $botUserAdmin->user;
            $index = 1;
            foreach ($levels as $level) {
                $nextUser = $this->prepareLevel($nextUser,
                    $admin,
                    $bot->id,
                    $event->amount,
                    $level,
                    $index
                );
                $index++;
            }
            return;
        }

        if ($event->directionEnum == CashBackDirectionEnum::Debiting) {
            if ($cashBack->amount - $event->amount < 0) {
                BotMethods::bot()
                    ->whereId($event->botId)
                    ->sendMessage(
                        $botUserAdmin->telegram_chat_id,
                        "На счету клиента недостаточно CashBack для списания.На балансе <b>$cashBack->amount  руб.</b>, а требуется <b>$event->amount  руб.</b>"
                    )
                    ->sendMessage(
                        $botUserUser->telegram_chat_id,
                        "На вашем счету недостаточно CashBack для списания.У вас <b>$cashBack->amount  руб.</b>, а требуется <b>$event->amount  руб.</b>",
                    );

                return;
            }

            $cashBack->amount += $event->amount;
            $cashBack->save();

            $tmpUser = BotMethods::prepareUserName($botUserUser);
            $tmpAdmin = BotMethods::prepareUserName($botUserAdmin);

            CashBackHistory::query()->create([
                'money_in_check' => 0,
                'amount' => $event->amount,
                'level' => 1,
                'description' => $event->info,
                'operation_type' => 0,
                'user_id' => $event->userId,
                'bot_id' => $event->botId,
                'employee_id' => $event->adminId,
            ]);

            BotMethods::bot()
                ->whereId($event->botId)
                ->sendMessage(
                    $botUserAdmin->telegram_chat_id,
                    "Вы успешно списали <b>  $event->amount руб.</b> CashBak у пользователя $tmpUser",
                )
                ->sendMessage(
                    $botUserUser->telegram_chat_id,
                    "С вашего счета успешно списано <b>$event->amount руб.</b> CashBak. Списание произвел администратор $tmpAdmin",
                );

        }


    }

    private function prepareLevel($user, $admin, $botId, $moneyAmount, $levelPercent, $levelIndex)
    {
        if (is_null($user))
            return null;

        $cashBack = $this->prepareUserCashBack($user->id, $botId);
        $tmpAmount = $moneyAmount * ($levelPercent / 100);
        $cashBack->amount += $tmpAmount;
        $cashBack->save();

        $name = BotMethods::prepareUserName($user);

        BotMethods::bot()
            ->whereId($botId)
            ->sendMessage(
                $user->telegram_chat_id,
                "Вам начислили <b>$tmpAmount руб.</b> CashBack $levelIndex уровня",
            )
            ->sendMessage(
                $admin->telegram_chat_id,
                "Вы начислили <b>$tmpAmount руб.</b> CashBack пользователю $name $levelIndex уровня",
            );


        CashBackHistory::query()->create([
            'money_in_check' => $moneyAmount,
            'amount' => $tmpAmount,
            'level' => $levelIndex,
            'description' => "Реферальное начислени CashBack $levelIndex уровня",
            'operation_type' => 1,
            'user_id' => $user->id,
            'bot_id' => $botId,
            'employee_id' => $admin->id,
        ]);

        return $user;

    }

    private function prepareUserCashBack($userId, $botId)
    {
        $cashBack = CashBack::query()->where("bot_id", $botId)
            ->where("user_id", $userId)
            ->first();

        if (is_null($cashBack))
            $cashBack = CashBack::query()->create([
                'user_id' => $userId,
                'bot_id' => $botId,
                'amount' => 0,
            ]);

        return $cashBack;

    }
}
