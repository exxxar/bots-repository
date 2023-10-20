<?php

namespace App\Enums;

enum CashBackDirectionEnum: int
{
    case Crediting = 1;
    case Debiting = 0;
    case Burning = 2;
    case None = 3;
}
