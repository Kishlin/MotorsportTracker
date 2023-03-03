<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class CarSeasonIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return CarSeasonId::class;
    }
}
