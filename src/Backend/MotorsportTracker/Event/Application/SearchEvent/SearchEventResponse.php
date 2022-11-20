<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchEventResponse implements Response
{
    private function __construct(
        private EventId $eventId,
    ) {
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public static function fromScalar(EventId $eventId): self
    {
        return new self($eventId);
    }
}
