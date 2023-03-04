<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendar;

use DateTimeImmutable;

interface ViewCalendarGateway
{
    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableCalendarView;
}
