<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Throwable;

final class EventResultsByRaceCreationFailedEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $eventId,
        private readonly Throwable $throwable,
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function throwable(): Throwable
    {
        return $this->throwable;
    }

    public static function withScalars(string $eventId, Throwable $throwable): self
    {
        return new self($eventId, $throwable);
    }
}