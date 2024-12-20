<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum;

enum EventGraphType: String
{
    case LAP_BY_LAP_PACE = 'lap-by-lap-pace';

    case TYRE_HISTORY = 'tyre-history';

    case POSITION_CHANGE = 'position-change';

    case FASTEST_LAP_DELTA = 'fastest-lap';
}
