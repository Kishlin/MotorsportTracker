<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\DomainEvent\VenueCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class VenueCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(VenueCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'venues',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Venue::Venue',
        );
    }
}
