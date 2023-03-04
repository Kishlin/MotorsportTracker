<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class CalendarEventStepViewIconType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewIcon::class;
    }
}
