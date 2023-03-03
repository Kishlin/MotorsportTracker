<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewReferenceId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class CalendarEventStepViewReferenceIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewReferenceId::class;
    }
}
