<?php

namespace App\Enums;

enum BotStatusEnum: int
{
    case Working = 1;
    case InMaintenance = 0;
    case Offline = 2;
    case NeedPayment = 3;
}
