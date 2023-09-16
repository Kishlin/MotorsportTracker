<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Logging\OnChampionshipCreationFailure;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\ChampionshipCreationFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class ChampionshipCreationFailureLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ChampionshipCreationFailureEvent $event): void
    {
        $this->logger->warning(
            'Failed to create Championship.',
            [
                'error' => $event->throwable()->getMessage(),
                'name'  => $event->command()->name(),
                'short' => $event->command()->shortName(),
                'code'  => $event->command()->shortCode(),
                'ref'   => $event->command()->ref(),
            ],
        );
    }
}
