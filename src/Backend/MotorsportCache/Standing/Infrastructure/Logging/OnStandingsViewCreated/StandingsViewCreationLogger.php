<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Logging\OnStandingsViewCreated;

use Kishlin\Backend\MotorsportCache\Standing\Domain\DomainEvent\StandingsViewCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class StandingsViewCreationLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(StandingsViewCreatedDomainEvent $event): void
    {
        $this->logger->info("Saved MotorsportCache::Standing::Standings #{$event->aggregateUuid()->value()}.");
    }
}
