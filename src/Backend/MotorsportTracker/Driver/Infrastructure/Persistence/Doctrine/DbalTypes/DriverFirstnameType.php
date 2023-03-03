<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class DriverFirstnameType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return StringValueObject::class;
    }
}
