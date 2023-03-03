<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class DriverIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverId::class;
    }
}
