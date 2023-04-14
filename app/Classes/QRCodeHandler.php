<?php

namespace App\Classes;

class QRCodeHandler
{

    protected $code;
    protected $telegramUserId;

    public function __construct($code, $telegramUserId)
    {
        $this->code = $code;
        $this->telegramUserId = $telegramUserId;
    }

  /*  public function handler(){
        switch ($this->code)
        {
            case "001": $this->askForAction(); break;
            case "001": $this->askForAction(); break;
        }
    }*/

   /* private function askForAction()
    {
        $question = Question::create("Какое действие выполнить?")
            ->addButtons([
                Button::create("Списать CashBack")->value('askforpay'),
                Button::create("Начислить CashBack")->value('addcashback'),
                Button::create("Добавить администратора")->value('addadmin'),
                Button::create("Убрать администратора")->value('removeadmin'),
                Button::create("Завершить работу")->value('stopcashback'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                if ($selectedValue == "askforpay") {
                    $this->askForPay();
                }

                if ($selectedValue == "addcashback") {
                    $this->askForCashback();
                }

                if ($selectedValue == "addadmin") {
                    $this->askForAddAdmin(true);
                }

                if ($selectedValue == "removeadmin") {
                    $this->askForAddAdmin(false);
                }


                if ($selectedValue == "stopcashback")
                    return;

            }
        });
    }*/

}
