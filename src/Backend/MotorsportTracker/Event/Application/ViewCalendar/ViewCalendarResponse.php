<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar;

use Kishlin\Backend\MotorsportTracker\Event\Domain\View\JsonableCalendarView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCalendarResponse implements Response
{
    private function __construct(
        private JsonableCalendarView $calendarView,
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
