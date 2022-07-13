<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;

final class DriverMoveDriverIdType extends AbstractUuidType
{
    protected function mappedClass(): string
    {
        return DriverMoveDriverId::class;
    }
}
