<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventSessionCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class VenueCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(EventSessionCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'venues',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Venue::Venue',
        );
    }
}
