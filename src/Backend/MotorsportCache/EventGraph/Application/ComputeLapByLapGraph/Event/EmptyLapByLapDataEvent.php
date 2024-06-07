<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class EmptyLapByLapDataEvent implements ApplicationEvent
{
    private function __construct(
        private string $session,
    ) {}

    public function session(): string
    {
        return $this->session;
    }

    public static function forSession(string $session): self
    {
        return new self($session);
    }
}
