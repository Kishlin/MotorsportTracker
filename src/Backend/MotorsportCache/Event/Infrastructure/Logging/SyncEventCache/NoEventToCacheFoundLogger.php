<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Logging\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Event\FoundNoEventToCacheEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class NoEventToCacheFoundLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(FoundNoEventToCacheEvent $event): void
    {
        $this->logger->notice('Found no events to cache.');
    }
}
