<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsTeamsCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class AnalyticsTeamsCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(AnalyticsTeamsCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'analytics_teams',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Standing::AnalyticsTeams',
        );
    }
}
