<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class EventCachedAlreadyExistException extends DomainException
{
}
