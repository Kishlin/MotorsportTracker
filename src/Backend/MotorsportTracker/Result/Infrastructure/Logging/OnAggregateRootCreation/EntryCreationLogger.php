<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class EntryCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(DriverCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'entry',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Result::Entry',
        );
    }
}
