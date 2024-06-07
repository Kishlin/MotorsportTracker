<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final readonly class ViewCalendarEventsResponse implements Response
{
    private function __construct(
        private JsonableEventsView $calendarView,
    ) {}

    public function calendarView(): JsonableEventsView
    {
        return $this->calendarView;
    }

    public static function fromView(JsonableEventsView $calendarView): self
    {
        return new self($calendarView);
    }
}
