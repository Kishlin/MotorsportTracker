<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class TeamCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(DriverCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'teams',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Team::Team',
        );
    }
}
