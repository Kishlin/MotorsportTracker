<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class DriverCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(DriverCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'drivers',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Driver::Driver',
        );
    }
}
