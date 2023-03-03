<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\IntValueObjectType;

final class EventIndexType extends IntValueObjectType
{
    protected function mappedClass(): string
    {
        return EventIndex::class;
    }
}
