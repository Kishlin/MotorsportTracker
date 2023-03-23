<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\RaceLapCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class RaceLapCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(RaceLapCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'race_lap',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Result::RaceLap',
        );
    }
}
