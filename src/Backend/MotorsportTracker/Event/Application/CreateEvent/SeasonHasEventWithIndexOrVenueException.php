<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class SeasonHasEventWithIndexOrVenueException extends DomainException
{
}
