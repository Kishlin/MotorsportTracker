<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class VenueCreationFailureException extends DomainException
{
}
