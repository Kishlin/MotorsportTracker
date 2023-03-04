<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SearchEventResponse implements Response
{
    private function __construct(
        private readonly UuidValueObject $eventId,
    ) {
    }

    public function eventId(): UuidValueObject
    {
        return $this->eventId;
    }

    public static function fromScalar(UuidValueObject $eventId): self
    {
        return new self($eventId);
    }
}
