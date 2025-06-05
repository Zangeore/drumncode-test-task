<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum TaskStatusesEnum: string
{
    use EnumUtils;

    case TODO = 'todo';
    case DONE = 'done';
}
