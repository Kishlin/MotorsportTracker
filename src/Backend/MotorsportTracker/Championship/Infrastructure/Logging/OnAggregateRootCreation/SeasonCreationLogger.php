<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class SeasonCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(ChampionshipCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'season',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Championship::Season',
        );
    }
}
