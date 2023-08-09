<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsDriversCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class AnalyticsDriversCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(AnalyticsDriversCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'analytics_drivers',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Standing::AnalyticsDrivers',
        );
    }
}
