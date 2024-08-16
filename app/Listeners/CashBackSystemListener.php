<?php

namespace App\Listeners;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Events\CashBackSubEvent;
use App\Facades\BotMethods;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\CashBackSub;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class CashBackSystemListener
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
    public function handle(CashBackSubEvent $event): void
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


        $cashBack = CashBack::query()
            ->firstOrCreate([
                'user_id' => $event->userId,
                'bot_id' => $event->botId,
                'bot_user_id' => $botUserUser->id,
            ], [
                'amount' => 0,
            ]);


        $cashBackSub = CashBackSub::query()->firstOrCreate([
            'cash_back_id' => $cashBack->id,
            'title' => $event->title,
        ], [
            'amount' => 0,
        ]);


        if ($event->directionEnum == CashBackDirectionEnum::Crediting) {

            $percent = $event->percent ?? $bot->level_1 ?? env("BASE_CASHBACK_LEVEL_1") ?? 0;

            $tmpAmount = $event->amount * ($percent / 100);
            $cashBackSub->amount += $tmpAmount;
            $cashBackSub->save();

            $name = BotMethods::prepareUserName($botUserUser);

            BotMethods::bot()
                ->whereId($event->botId)
                ->sendMessage(
                    $botUserUser->telegram_chat_id,
                    "Вам начислили <b>$tmpAmount руб.</b> CashBack в категории \"$event->title\"",
                )
                ->sendMessage(
                    $botUserAdmin->telegram_chat_id,
                    "Вы начислили <b>$tmpAmount руб.</b> CashBack пользователю $name в категории \"$event->title\"",
                );

            $this->checkWarnings($event->amount, CashBackDirectionEnum::None, 1);
            $this->checkWarnings($tmpAmount, CashBackDirectionEnum::Crediting, 1);

            CashBackHistory::query()->create([
                'money_in_check' => $event->amount,
                'amount' => $tmpAmount,
                'level' => 1,
                'description' => "Специальное начисление CashBack 1 уровня в категории \"$event->title\"",
                'operation_type' => 1,
                'user_id' => $botUserUser->user_id,
                'bot_id' => $event->botId,
                'employee_id' => $botUserAdmin->user_id,
            ]);

            if ($event->needUserReview)
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
            if ($cashBackSub->amount - $event->amount < 0) {
                BotMethods::bot()
                    ->whereId($event->botId)
                    ->sendMessage(
                        $botUserAdmin->telegram_chat_id,
                        "На специальном счету \"$cashBackSub->title\" клиента недостаточно CashBack для списания.На балансе <b>$cashBackSub->amount  руб.</b>, а требуется <b>$event->amount  руб.</b>"
                    )
                    ->sendMessage(
                        $botUserUser->telegram_chat_id,
                        "На вашем cпециальном счету \"$cashBackSub->title\" недостаточно CashBack для списания.У вас <b>$cashBackSub->amount  руб.</b>, а требуется <b>$event->amount  руб.</b>",
                    );

                return;
            }

            $cashBackSub->amount -= $event->amount;
            $cashBackSub->save();

            $this->checkWarnings($event->amount, CashBackDirectionEnum::Debiting);

            $tmpUser = BotMethods::prepareUserName($botUserUser);
            $tmpAdmin = BotMethods::prepareUserName($botUserAdmin);

            CashBackHistory::query()->create([
                'money_in_check' => 0,
                'amount' => $event->amount,
                'level' => 1,
                'description' => "Списание специального CashBack в категории $cashBackSub->title: $event->info",
                'operation_type' => 0,
                'user_id' => $event->userId,
                'bot_id' => $event->botId,
                'employee_id' => $event->adminId,
            ]);

            BotMethods::bot()
                ->whereId($event->botId)
                ->sendMessage(
                    $botUserAdmin->telegram_chat_id,
                    "Вы успешно списали <b>  $event->amount руб.</b> CashBack у пользователя $tmpUser с его специального счета",
                )
                ->sendMessage(
                    $botUserUser->telegram_chat_id,
                    "С вашего специального счета \"$cashBackSub->title\" успешно списано <b>$event->amount руб.</b> CashBack. Списание произвел администратор $tmpAdmin",
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
                    $bot->order_channel ?? null,
                    "🚨🚨🚨🚨\n$this->warnText\nОперация выполнена администратором $nameAdmin ($tgAdminId) для пользователя $nameUser ($tgUserId)",
                    $thread
                );
        }


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


}
