<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ClassificationCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class ClassificationCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(ClassificationCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'classification',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Result::Classification',
        );
    }
}
