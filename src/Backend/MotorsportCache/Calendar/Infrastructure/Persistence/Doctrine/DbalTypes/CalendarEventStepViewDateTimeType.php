<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\DatetimeValueObjectType;

final class CalendarEventStepViewDateTimeType extends DatetimeValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewDateTime::class;
    }
}
