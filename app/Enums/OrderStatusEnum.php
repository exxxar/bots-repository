<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case InDelivery = 1;
    case Completed = 2;
    case Decline = 3;
    case NewOrder = 0;
}
