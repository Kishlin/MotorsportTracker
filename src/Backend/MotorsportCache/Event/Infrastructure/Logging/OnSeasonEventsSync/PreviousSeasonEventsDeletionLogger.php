<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Logging\OnSeasonEventsSync;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\Event\PreviousSeasonEventsDeletedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class PreviousSeasonEventsDeletionLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(PreviousSeasonEventsDeletedEvent $event): void
    {
        $this->logger->info(sprintf('Deleted previous season events for %s #%d.', $event->championship(), $event->year()));
    }
}
