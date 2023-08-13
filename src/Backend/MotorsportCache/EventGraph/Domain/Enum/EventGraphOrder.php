<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum;

enum EventGraphOrder: Int
{
    case LAP_BY_LAP_PACE = 500;

    case TYRE_HISTORY = 1000;

    case POSITION_CHANGE = 1500;
}
