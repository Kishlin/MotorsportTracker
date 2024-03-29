<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class ChampionshipCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(ChampionshipCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'championship',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Championship::Championship',
        );
    }
}
