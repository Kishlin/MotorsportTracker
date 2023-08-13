<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphType;
use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class DeprecatedGraphDeletedEvent implements ApplicationEvent
{
    private function __construct(
        private string $event,
        private EventGraphType $type,
    ) {
    }

    public function event(): string
    {
        return $this->event;
    }

    public function type(): EventGraphType
    {
        return $this->type;
    }

    public static function forEvent(string $event, EventGraphType $type): self
    {
        return new self($event, $type);
    }
}
