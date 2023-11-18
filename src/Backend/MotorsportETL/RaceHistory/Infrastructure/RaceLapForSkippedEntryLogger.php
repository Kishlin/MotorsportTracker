<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Infrastructure;

use Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory\RaceLapForSkippedEntryEvent;
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
