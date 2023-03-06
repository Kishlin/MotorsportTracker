<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCalendarResponse implements Response
{
    private function __construct(
        private readonly JsonableCalendarView $calendarView,
    ) {
    }

    public function calendarView(): JsonableCalendarView
    {
        return $this->calendarView;
    }

    public static function fromView(JsonableCalendarView $calendarView): self
    {
        return new self($calendarView);
    }
}
