<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class PreviousEventResultsByRaceDeletedEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $eventId,
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public static function forEvent(string $eventId): self
    {
        return new self($eventId);
    }
}
