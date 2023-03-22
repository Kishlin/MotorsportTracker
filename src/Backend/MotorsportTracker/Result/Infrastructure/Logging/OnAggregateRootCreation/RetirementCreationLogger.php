<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\RetirementCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class RetirementCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(RetirementCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'retirement',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Result::Retirement',
        );
    }
}
