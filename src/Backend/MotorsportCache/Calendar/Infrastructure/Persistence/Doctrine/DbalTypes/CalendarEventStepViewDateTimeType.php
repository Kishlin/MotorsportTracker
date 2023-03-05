<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\DateTimeValueObjectType;

final class CalendarEventStepViewDateTimeType extends DateTimeValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewDateTime::class;
    }
}
