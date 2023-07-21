<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\ConstructorTeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class ConstructorTeamCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(ConstructorTeamCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'constructor_team',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Team::ConstructorTeam',
        );
    }
}
