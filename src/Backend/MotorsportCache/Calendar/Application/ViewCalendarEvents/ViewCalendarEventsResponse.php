<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCalendarEventsResponse implements Response
{
    private function __construct(
        private readonly JsonableCalendarEventsView $calendarView,
    ) {
    }

    public function calendarView(): JsonableCalendarEventsView
    {
        return $this->calendarView;
    }

    public static function fromView(JsonableCalendarEventsView $calendarView): self
    {
        return new self($calendarView);
    }
}
