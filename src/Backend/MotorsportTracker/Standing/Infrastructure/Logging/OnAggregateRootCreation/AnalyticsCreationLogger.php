<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class AnalyticsCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(AnalyticsCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'analytics',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Standing::Analytics',
        );
    }
}
