<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class VenueIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return VenueId::class;
    }
}
