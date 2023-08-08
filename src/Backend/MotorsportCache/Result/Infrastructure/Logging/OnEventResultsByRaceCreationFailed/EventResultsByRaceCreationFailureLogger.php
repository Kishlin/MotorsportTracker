<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnEventResultsByRaceCreationFailed;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Event\EventResultsBySessionsCreationFailedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class EventResultsByRaceCreationFailureLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(EventResultsBySessionsCreationFailedEvent $event): void
    {
        $throwable = $event->throwable();

        $this->logger->error(
            "Failed to create EventResultsByRace for event {$event->eventId()}",
            [
                'message' => $throwable->getMessage(),
                'class'   => get_class($throwable),
                'file'    => $throwable->getFile(),
                'line'    => $throwable->getLine(),
            ]
        );
    }
}
