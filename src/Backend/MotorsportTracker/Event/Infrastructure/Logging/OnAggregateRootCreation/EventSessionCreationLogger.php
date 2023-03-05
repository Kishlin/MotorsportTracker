<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventSessionCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class EventSessionCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(EventSessionCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'event_sessions',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Event::EventSession',
        );
    }
}
