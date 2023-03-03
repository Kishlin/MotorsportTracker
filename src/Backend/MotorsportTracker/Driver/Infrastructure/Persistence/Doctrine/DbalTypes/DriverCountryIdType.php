<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverCountryId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class DriverCountryIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return DriverCountryId::class;
    }
}
