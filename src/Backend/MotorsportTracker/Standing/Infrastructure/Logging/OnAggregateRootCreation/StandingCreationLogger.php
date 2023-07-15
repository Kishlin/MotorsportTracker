<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\StandingCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class StandingCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(StandingCreatedDomainEvent $event): void
    {
        $table = 'standing_' . $event->standingType()->toString();

        $this->logCreation($table, $event->aggregateUuid()->value(), 'MotorsportTracker::Standing::Standing');
    }
}
