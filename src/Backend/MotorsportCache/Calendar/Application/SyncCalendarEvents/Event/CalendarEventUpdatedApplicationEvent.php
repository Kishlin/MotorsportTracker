<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CalendarEventUpdatedApplicationEvent implements ApplicationEvent
{
    private function __construct(
        private readonly StringValueObject $calendarEventSlug,
    ) {
    }

    public function calendarEventSlug(): StringValueObject
    {
        return $this->calendarEventSlug;
    }

    public static function forCalendarEvent(CalendarEvent $event): self
    {
        return new self($event->slug());
    }
}
