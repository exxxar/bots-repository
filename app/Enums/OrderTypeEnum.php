<?php

namespace App\Enums;

enum OrderTypeEnum: int
{
    case ExternalStore = 2;
    case Constructor = 1;
    case InternalStore = 0;

}
