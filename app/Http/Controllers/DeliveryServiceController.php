<?php

namespace App\Http\Controllers;


use App\Facades\BotManager;
use Illuminate\Http\Request;

class DeliveryServiceController extends Controller
{
    //

    public function main(){
        BotManager::bot()
            ->sendReplyMenu("Главное меню", "main_menu_deliveryman_1");
    }

    public function profile(){
        BotManager::bot()
            ->reply("profile");
    }

    public function orders(){
        BotManager::bot()
            ->sendReplyMenu("Заказы","main_menu_deliveryman_3");
    }

    public function statistic(){
        BotManager::bot()
            ->sendReplyMenu("statistic","main_menu_deliveryman_2");
    }

    public function statisticDelivery(){

        $summaryDeliveryCount = 0;
        $lastYearDeliveryCount = 0;
        $lastMonthDeliveryCount = 0;
        $lastWeekDeliveryCount = 0;
        $lastDecadeDeliveryCount = 0;
        $currentDayDeliveryCount = 0;
        $failedDeliveryCount = 0;
        $positiveReviewDeliveryCount = 0;
        $negativeReviewDeliveryCount = 0;
        $message = sprintf("<b>Статистика доставок:</b>
❗ Всего выполнено заказов: <b>%s</b>
❗ Заказов за последний год: <b>%s</b>
❗ Заказов за последний месяц: <b>%s</b>
❗ Заказов за последнюю неделю: <b>%s</b>
❗ Заказов за последние 10 дней: <b>%s</b>
❗ Заказов за текущий день: <b>%s</b>
❗ Неудачных заказов: <b>%s</b>
❗ Число заказов с положительным отзывом: <b>%s</b>
❗ Число заказов с отрицательным отзывом: <b>%s</b>",
            $summaryDeliveryCount,
            $lastYearDeliveryCount,
            $lastMonthDeliveryCount,
            $lastWeekDeliveryCount,
            $lastDecadeDeliveryCount,
            $currentDayDeliveryCount,
            $failedDeliveryCount,
            $positiveReviewDeliveryCount,
            $negativeReviewDeliveryCount

        );
        BotManager::bot()
            ->reply("$message");
    }

    public function statisticMoney(){
        $summaryOrdersMoneyCount = 0;
        $summaryDeliveryMoneyCount = 0;
        $lastYearDeliveryCount = 0;
        $lastMonthDeliveryCount = 0;
        $lastWeekDeliveryCount = 0;
        $lastDecadeDeliveryCount = 0;
        $currentDayDeliveryCount = 0;

        $message = sprintf("<b>Статистика дохода доставщика:</b>
❗ Всего сумма заказанных товаров: <b>%s</b>
❗ Всего сумма оплаченной доставки: <b>%s</b>
❗ Сумма за последний год: <b>%s</b>
❗ Сумма за последний месяц: <b>%s</b>
❗ Сумма за последнюю неделю: <b>%s</b>
❗ Сумма за последние 10 дней: <b>%s</b>
❗ Сумма за текущий день: <b>%s</b>",
            $summaryOrdersMoneyCount,
            $summaryDeliveryMoneyCount,
            $lastYearDeliveryCount,
            $lastMonthDeliveryCount,
            $lastWeekDeliveryCount,
            $lastDecadeDeliveryCount,
            $currentDayDeliveryCount
        );

        BotManager::bot()
            ->reply("$message");
    }

    public function currentOrders(){
        BotManager::bot()
            ->reply("currentOrders");
    }

    public function finishedOrders(){
        BotManager::bot()
            ->reply("finishedOrders");
    }

    public function support(){
        BotManager::bot()
            ->reply("support");
    }

    public function rules(){
        BotManager::bot()
            ->reply("rules");
    }

    public function searchOrders(){
        BotManager::bot()
            ->reply("searchOrders");
    }

}
