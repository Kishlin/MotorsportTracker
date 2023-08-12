<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\EntryAdditionalDriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class EntryAdditionalDriverCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(EntryAdditionalDriverCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'entry_additional_driver',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Result::EntryAdditionalDriver',
        );
    }
}
