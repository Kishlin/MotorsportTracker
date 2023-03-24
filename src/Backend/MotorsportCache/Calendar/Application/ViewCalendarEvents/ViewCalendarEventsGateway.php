<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;

interface ViewCalendarEventsGateway
{
    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableEventsView;
}
