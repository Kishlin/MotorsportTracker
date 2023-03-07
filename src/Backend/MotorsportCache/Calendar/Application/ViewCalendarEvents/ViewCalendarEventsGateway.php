<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents;

use DateTimeImmutable;

interface ViewCalendarEventsGateway
{
    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableCalendarEventsView;
}
