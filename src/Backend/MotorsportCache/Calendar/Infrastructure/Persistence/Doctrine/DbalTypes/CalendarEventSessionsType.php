<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSessions;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\JsonValueObjectType;

final class CalendarEventSessionsType extends JsonValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventSessions::class;
    }
}
