<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractStringType;

final class VenueNameType extends AbstractStringType
{
    protected function mappedClass(): string
    {
        return VenueName::class;
    }
}
