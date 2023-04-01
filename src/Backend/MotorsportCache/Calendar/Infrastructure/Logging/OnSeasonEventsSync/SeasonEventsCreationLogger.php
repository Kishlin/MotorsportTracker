<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Logging\OnSeasonEventsSync;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\DomainEvent\SeasonEventsCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class SeasonEventsCreationLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SeasonEventsCreatedDomainEvent $event): void
    {
        $this->logger->info("Successfully created SeasonEvents #{$event->aggregateUuid()->value()}.");
    }
}
