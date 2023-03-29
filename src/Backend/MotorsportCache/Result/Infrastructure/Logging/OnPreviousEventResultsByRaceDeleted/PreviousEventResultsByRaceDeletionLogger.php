<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnPreviousEventResultsByRaceDeleted;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\PreviousEventResultsByRaceDeletedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class PreviousEventResultsByRaceDeletionLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(PreviousEventResultsByRaceDeletedEvent $event): void
    {
        $this->logger->notice("Deleted previous EventResultsByRace for {$event->eventId()}.");
    }
}
