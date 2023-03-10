<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class EntryCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(DriverCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'entries',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Entry::Entry',
        );
    }
}
