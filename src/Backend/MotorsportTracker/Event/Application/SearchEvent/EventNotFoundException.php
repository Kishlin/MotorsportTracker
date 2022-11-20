<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class EventNotFoundException extends DomainException
{
}
