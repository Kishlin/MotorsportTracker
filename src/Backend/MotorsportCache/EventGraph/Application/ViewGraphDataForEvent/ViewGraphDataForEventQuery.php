<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewGraphDataForEventQuery implements Query
{
    private function __construct(
        private readonly string $eventId,
    ) {}

    public function eventId(): string
    {
        return $this->eventId;
    }

    public static function fromScalars(string $eventId): self
    {
        return new self($eventId);
    }
}
