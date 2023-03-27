<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnEventResultsByRaceCreated;

use Kishlin\Backend\MotorsportCache\Result\Domain\DomainEvent\EventResultsByRaceCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class EventsResultsByRaceCreationLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(EventResultsByRaceCreatedDomainEvent $event): void
    {
        $this->logger->info("Saved MotorsportCache::Result::EventResultsByRace #{$event->aggregateUuid()->value()}.");
    }
}
