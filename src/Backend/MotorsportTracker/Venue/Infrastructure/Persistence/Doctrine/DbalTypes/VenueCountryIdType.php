<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class VenueCountryIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return VenueCountryId::class;
    }
}
