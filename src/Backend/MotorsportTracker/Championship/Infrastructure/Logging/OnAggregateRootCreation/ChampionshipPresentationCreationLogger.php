<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipPresentationCreatedDomainEvent;
use Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation\AggregateRootCreationLogger;

final class ChampionshipPresentationCreationLogger extends AggregateRootCreationLogger
{
    public function __invoke(ChampionshipPresentationCreatedDomainEvent $event): void
    {
        $this->logCreation(
            'championship_presentations',
            $event->aggregateUuid()->value(),
            'MotorsportTracker::Championship::ChampionshipPresentation',
        );
    }
}
