<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

abstract readonly class ComputeGraphCommand implements Command
{
    protected function __construct(
        private string $eventId,
    ) {}

    public function eventId(): string
    {
        return $this->eventId;
    }
}
