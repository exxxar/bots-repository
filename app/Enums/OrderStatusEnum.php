<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case InDelivery = 1;
    case Completed = 2;
    case Decline = 3;
    case ReadyForDelivery = 4;
    case StartsCooking = 5;
    case NewOrder = 0;
}
