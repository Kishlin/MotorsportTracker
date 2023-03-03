<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class DriverStandingDriverIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverStandingDriverId::class;
    }
}
