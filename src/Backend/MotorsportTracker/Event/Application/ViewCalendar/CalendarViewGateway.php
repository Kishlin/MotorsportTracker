<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar;

use Kishlin\Backend\MotorsportTracker\Event\Domain\View\JsonableCalendarView;

interface CalendarViewGateway
{
    public function view(): JsonableCalendarView;
}
