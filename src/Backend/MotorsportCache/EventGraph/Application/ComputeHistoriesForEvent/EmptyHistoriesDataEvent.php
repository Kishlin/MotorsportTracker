<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class EmptyHistoriesDataEvent implements ApplicationEvent
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
