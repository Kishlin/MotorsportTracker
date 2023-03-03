<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class EventIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return EventId::class;
    }
}
