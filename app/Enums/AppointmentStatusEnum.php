<?php

namespace App\Enums;

enum AppointmentStatusEnum: int
{
    case Start = 0;
    case Confirm = 1;
    case Complete = 2;
    case Cancel = 3;
}
