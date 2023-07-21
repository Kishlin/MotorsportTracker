<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Logging\OnStandingsViewCreationFailed;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Event\StandingsCreationFailedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class StandingsViewCreationFailureLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(StandingsCreationFailedEvent $event): void
    {
        $throwable = $event->throwable();

        $this->logger->error(
            "Failed to create standings for {$event->championship()} {$event->year()}.",
            [
                'message' => $throwable->getMessage(),
                'class'   => get_class($throwable),
                'file'    => $throwable->getFile(),
                'line'    => $throwable->getLine(),
            ]
        );
    }
}
