<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class CarIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return CarId::class;
    }
}
