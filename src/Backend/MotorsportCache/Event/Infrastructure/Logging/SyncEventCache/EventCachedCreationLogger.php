<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Logging\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Domain\DomainEvent\EventCachedCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class EventCachedCreationLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(EventCachedCreatedDomainEvent $event): void
    {
        $this->logger->info("Successfully created EventCached #{$event->aggregateUuid()->value()}.");
    }
}
