<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;

interface SaveCalendarEventGateway
{
    public function save(CalendarEvent $event): CalendarEventUpsert;
}
