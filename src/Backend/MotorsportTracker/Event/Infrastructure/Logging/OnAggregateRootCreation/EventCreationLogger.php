<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class EventCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(EventCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'events',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Event::Event',
        );
    }
}
