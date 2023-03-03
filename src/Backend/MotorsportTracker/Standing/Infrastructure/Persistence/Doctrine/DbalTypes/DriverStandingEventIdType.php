<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class DriverStandingEventIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverStandingEventId::class;
    }
}
