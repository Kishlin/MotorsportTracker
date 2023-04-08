<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class DeprecatedLapByLapGraphDeletedEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $event,
    ) {
    }

    public function event(): string
    {
        return $this->event;
    }

    public static function forEvent(string $event): self
    {
        return new self($event);
    }
}
