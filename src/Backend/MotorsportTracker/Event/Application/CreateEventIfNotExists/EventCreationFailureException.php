<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class EventCreationFailureException extends DomainException
{
}
