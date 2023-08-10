<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnRaceLapForSkippedEntry;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\RaceLapForSkippedEntryEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class RaceLapForSkippedEntryLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(RaceLapForSkippedEntryEvent $event): void
    {
        $this->logger->warning(
            "Found a race lap for skipped entry, session {$event->session()} car #{$event->carNumber()}",
            $event->carPosition(),
        );
    }
}
