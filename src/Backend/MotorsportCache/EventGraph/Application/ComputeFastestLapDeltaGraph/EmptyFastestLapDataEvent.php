<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class EmptyFastestLapDataEvent implements ApplicationEvent
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
