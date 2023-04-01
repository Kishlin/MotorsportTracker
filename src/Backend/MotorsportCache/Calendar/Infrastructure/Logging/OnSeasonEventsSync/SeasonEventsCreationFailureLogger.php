<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Logging\OnSeasonEventsSync;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Event\SeasonEventsCreationFailedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class SeasonEventsCreationFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SeasonEventsCreationFailedEvent $event): void
    {
        $throwable = $event->throwable();

        $this->logger->error(
            "Failed to create SeasonEvents for season {$event->championship()} #{$event->year()}.",
            [
                'message' => $throwable->getMessage(),
                'class'   => get_class($throwable),
                'file'    => $throwable->getFile(),
                'line'    => $throwable->getLine(),
            ]
        );
    }
}
