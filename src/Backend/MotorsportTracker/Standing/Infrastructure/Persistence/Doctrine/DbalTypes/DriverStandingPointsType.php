<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\FloatValueObjectType;

final class DriverStandingPointsType extends FloatValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverStandingPoints::class;
    }
}
