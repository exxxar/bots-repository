<?php

namespace App\Enums;

enum CustomFieldTypeEnum: int
{
    case Text = 0;
    case Number = 1;
    case Boolean = 2;
    case Datetime = 3;

}
