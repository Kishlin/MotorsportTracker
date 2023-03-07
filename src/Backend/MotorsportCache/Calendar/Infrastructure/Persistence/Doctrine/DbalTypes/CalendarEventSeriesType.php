<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\ArrayValueObjectType;

final class CalendarEventSeriesType extends ArrayValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventSeries::class;
    }
}
