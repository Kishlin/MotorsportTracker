<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class DriverNameType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverName::class;
    }
}
