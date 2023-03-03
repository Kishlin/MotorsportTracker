<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\IntValueObjectType;

final class CarNumberType extends IntValueObjectType
{
    protected function mappedClass(): string
    {
        return CarNumber::class;
    }
}
