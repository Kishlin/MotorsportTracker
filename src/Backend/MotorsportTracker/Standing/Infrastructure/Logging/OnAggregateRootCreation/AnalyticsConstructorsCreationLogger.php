<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsConstructorsCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class AnalyticsConstructorsCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(AnalyticsConstructorsCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'analytics_constructors',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Standing::AnalyticsConstructors',
        );
    }
}
