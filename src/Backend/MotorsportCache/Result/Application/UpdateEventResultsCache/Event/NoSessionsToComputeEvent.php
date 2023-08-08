<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class NoSessionsToComputeEvent implements ApplicationEvent
{
    private function __construct(
        private string $eventId,
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
