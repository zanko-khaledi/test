<?php

namespace App\Enums;

use App\Enums\Utils;
use App\Enums\Utils\UseEnumsTrait;

enum TaskStatus:int
{
    use UseEnumsTrait;
    case IN_PROGRESS = 0;
    case DONE = 1;
}
