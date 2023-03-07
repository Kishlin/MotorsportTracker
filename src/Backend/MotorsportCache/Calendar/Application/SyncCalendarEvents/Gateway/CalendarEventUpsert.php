<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway;

enum CalendarEventUpsert
{
    case CREATED;

    case UPDATED;
}
