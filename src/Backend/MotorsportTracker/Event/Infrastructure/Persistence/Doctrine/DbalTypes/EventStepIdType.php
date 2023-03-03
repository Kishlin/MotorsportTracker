<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class EventStepIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return EventStepId::class;
    }
}
