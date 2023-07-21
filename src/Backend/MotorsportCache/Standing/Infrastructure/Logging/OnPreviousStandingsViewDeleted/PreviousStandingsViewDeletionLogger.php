<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Logging\OnPreviousStandingsViewDeleted;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Event\PreviousStandingsDeletedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class PreviousStandingsViewDeletionLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(PreviousStandingsDeletedEvent $event): void
    {
        $this->logger->notice("Deleted previous standings for {$event->championship()} {$event->year()}.");
    }
}
