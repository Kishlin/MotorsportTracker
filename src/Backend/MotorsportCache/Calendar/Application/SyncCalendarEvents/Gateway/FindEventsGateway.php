<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

interface FindEventsGateway
{
    /**
     * @return CalendarEventEntry[]
     */
    public function findAll(StringValueObject $seriesSlug, PositiveIntValueObject $year): array;
}
