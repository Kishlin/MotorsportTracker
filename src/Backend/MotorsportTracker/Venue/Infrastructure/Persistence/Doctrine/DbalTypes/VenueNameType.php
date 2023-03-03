<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class VenueNameType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return VenueName::class;
    }
}
