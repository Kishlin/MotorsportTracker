<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\DatetimeValueObjectType;

final class EventStepDateTimeType extends DatetimeValueObjectType
{
    protected function mappedClass(): string
    {
        return EventStepDateTime::class;
    }
}
