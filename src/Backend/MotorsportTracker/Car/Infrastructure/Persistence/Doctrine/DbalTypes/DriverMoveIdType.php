<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class DriverMoveIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverMoveId::class;
    }
}
