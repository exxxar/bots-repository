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
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class CashBackListener
{


    protected $warnings;

    protected $warnText;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->warnText = "";
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


        $this->warnings = $bot->warnings ?? [];

        $botUserAdmin = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $event->botId)
            ->where("user_id", $event->adminId)
            ->first();

        $botUserUser = BotUser::query()
            ->with(["user", "parent"])
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


        $cashBack = $this->prepareUserCashBack($event->botId, $botUserUser->id);

        if ($event->directionEnum == CashBackDirectionEnum::Crediting) {


            if (is_null($event->percent)) {
                $levels[] = $bot->level_1 ?? env("BASE_CASHBACK_LEVEL_1") ?? 0;
                $levels[] = $bot->level_2 ?? env("BASE_CASHBACK_LEVEL_2") ?? 0;
                $levels[] = $bot->level_3 ?? env("BASE_CASHBACK_LEVEL_3") ?? 0;
            } else
                $levels[] = $event->percent ?? $bot->level_1 ?? env("BASE_CASHBACK_LEVEL_1") ?? 0;


            $nextBotUser = $botUserUser;
            $index = 1;
            //   Log::info("levels=".print_r($levels, true));
            foreach ($levels as $level) {

                //   Log::info("nextBotUser $nextBotUser->id botUserAdmin $botUserAdmin->id level=$level index=$index");

                $this->prepareLevel(
                    $nextBotUser,
                    $botUserAdmin,
                    $bot->id,
                    $event->amount,
                    $level,
                    $index
                );

                // Log::info("parent=".print_r($nextBotUser->parent_id, true));

                $nextBotUser = BotUser::query()
                    ->with(["user", "parent"])
                    ->where("id", $nextBotUser->parent_id)
                    ->first();


                if (is_null($nextBotUser)) {
                    Log::info("break");
                    break;
                }

                $index++;
            }

            BotMethods::bot()
                ->whereBot($bot)
                ->sendInlineKeyboard(
                    $botUserUser->telegram_chat_id,
                    "Пожалуйста, поставьте оценку нашей работе!", [
                        [
                            ["text" => "😡", "callback_data" => "/send_review 0"],
                            ["text" => "😕", "callback_data" => "/send_review 1"],
                            ["text" => "😐", "callback_data" => "/send_review 2"],
                            ["text" => "🙂", "callback_data" => "/send_review 3"],
                            ["text" => "😁", "callback_data" => "/send_review 4"],
                        ]
                    ]
                );
        }

        if ($event->directionEnum == CashBackDirectionEnum::Debiting) {
            if ($cashBack->amount - $event->amount < 0) {
                BotMethods::bot()
                    ->whereId($event->botId)
                    ->sendMessage(
                        $botUserAdmin->telegram_chat_id,
                        "На счету клиента недостаточно CashBack для списания. На балансе <b>$cashBack->amount  руб.</b>, а требуется <b>$event->amount  руб.</b>"
                    )
                    ->sendMessage(
                        $botUserUser->telegram_chat_id,
                        "На вашем счету недостаточно CashBack для списания. У вас <b>$cashBack->amount  руб.</b>, а требуется <b>$event->amount  руб.</b>",
                    );

                return;
            }

            $cashBack->amount -= $event->amount;
            $cashBack->save();

            $this->checkWarnings($event->amount, CashBackDirectionEnum::Debiting);

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

        if (strlen($this->warnText) > 0) {
            $tgAdminId = $botUserAdmin->telegram_chat_id ?? 'Не указано';
            $tgUserId = $botUserUser->telegram_chat_id ?? 'Не указано';
            $nameAdmin = BotMethods::prepareUserName($botUserAdmin);
            $nameUser = BotMethods::prepareUserName($botUserUser);

            $thread = $bot->topics["cashback"] ?? null;

            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $bot->order_channel ??  null,
                    "🚨🚨🚨🚨\n$this->warnText\nОперация выполнена администратором $nameAdmin ($tgAdminId) для пользователя $nameUser ($tgUserId)",
                    $thread
                );
        }


    }

    private function prepareLevel($userBotUser, $adminBotUser, $botId, $moneyAmount, $levelPercent, $levelIndex)
    {


        if (is_null($userBotUser) || $moneyAmount == 0 || $levelPercent == 0)
            return null;

        $tmpAmount = $moneyAmount * ($levelPercent / 100);
        // Log::info("cashback level=$levelIndex amount=$moneyAmount %=$levelPercent");
        $cashBack = $this->prepareUserCashBack($botId, $userBotUser->id);
        $cashBack->amount += $tmpAmount;
        $cashBack->save();

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereId($botId)
            ->sendMessage(
                $userBotUser->telegram_chat_id,
                "Вам начислили <b>$tmpAmount руб.</b> CashBack $levelIndex уровня",
            )
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы начислили <b>$tmpAmount руб.</b> CashBack пользователю $name $levelIndex уровня",
            );

        $this->checkWarnings($moneyAmount, CashBackDirectionEnum::None, $levelIndex);
        $this->checkWarnings($tmpAmount, CashBackDirectionEnum::Crediting, $levelIndex);

        CashBackHistory::query()->create([
            'money_in_check' => $moneyAmount,
            'amount' => $tmpAmount,
            'level' => $levelIndex,
            'description' => "Реферальное начисление CashBack $levelIndex уровня",
            'operation_type' => 1,
            'user_id' => $userBotUser->user_id,
            'bot_id' => $botId,
            'employee_id' => $adminBotUser->user_id,
        ]);


    }

    private function checkWarnings($amount, $direction, $levelIndex = null)
    {


        if (empty($this->warnings))
            return;

        foreach ($this->warnings as $warn) {
            if (!$warn->is_active)
                continue;

            if ($warn->rule_key == "bill_sum_more_then"
                && $amount >= $warn->rule_value
                && $direction == CashBackDirectionEnum::None
            ) {

                $this->warnText .= "Внимание! Сумма чека $amount руб. > $warn->rule_value руб (для уровня $levelIndex)\n";
            }

            if ($warn->rule_key == "cashback_up_sum_more_then"
                && $amount >= $warn->rule_value
                && $direction == CashBackDirectionEnum::Crediting

            ) {
                $this->warnText .= "Внимание! Сумма начисления CashBack $amount руб.  > $warn->rule_value руб (для уровня $levelIndex) \n";
            }

            if ($warn->rule_key == "cashback_down_sum_more_then"
                && $amount >= $warn->rule_value
                && $direction == CashBackDirectionEnum::Debiting
            ) {
                $this->warnText .= "Внимание! Сумма списания CashBack $amount руб.\n";
            }
        }

    }

    private function prepareUserCashBack($botId, $botUserId)
    {
        $cashBack = CashBack::query()->where("bot_id", $botId)
            ->where("bot_user_id", $botUserId)
            ->first();

        if (is_null($cashBack))
            $cashBack = CashBack::query()->create([
                'bot_id' => $botId,
                'bot_user_id' => $botUserId,
                'amount' => 0,
            ]);

        return $cashBack;

    }
}
