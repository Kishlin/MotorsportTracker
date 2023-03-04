<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewReferenceId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class CalendarEventStepViewReferenceIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return CalendarEventStepViewReferenceId::class;
    }
}
