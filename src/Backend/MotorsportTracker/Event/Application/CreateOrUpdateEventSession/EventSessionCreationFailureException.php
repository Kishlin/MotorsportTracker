<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class EventSessionCreationFailureException extends DomainException
{
}
