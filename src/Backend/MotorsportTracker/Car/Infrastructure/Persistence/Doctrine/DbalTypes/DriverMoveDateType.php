<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\DatetimeValueObjectType;

final class DriverMoveDateType extends DatetimeValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverMoveDate::class;
    }
}
