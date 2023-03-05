<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\SessionTypeCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class SessionTypeCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(SessionTypeCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'session_types',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Event::SessionType',
        );
    }
}
