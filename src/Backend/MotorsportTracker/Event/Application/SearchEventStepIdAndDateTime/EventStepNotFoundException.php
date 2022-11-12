<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class EventStepNotFoundException extends DomainException
{
}
