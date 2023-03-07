<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

interface FindSeriesGateway
{
    public function findForSlug(StringValueObject $seriesSlug, PositiveIntValueObject $year): ?CalendarEventSeries;
}
