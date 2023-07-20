<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class ConstructorCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(TeamCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'constructor',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Team::Constructor',
        );
    }
}
