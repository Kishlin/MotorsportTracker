<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractDatetimeType;

final class CalendarEventStepViewDateTimeType extends AbstractDatetimeType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewDateTime::class;
    }
}
