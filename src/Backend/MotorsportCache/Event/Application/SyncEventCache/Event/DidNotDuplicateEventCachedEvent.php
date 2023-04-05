<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class DidNotDuplicateEventCachedEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
        private readonly string $event,
    ) {
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function event(): string
    {
        return $this->event;
    }

    public static function forEvent(string $championship, int $year, string $event): self
    {
        return new self($championship, $year, $event);
    }
}
