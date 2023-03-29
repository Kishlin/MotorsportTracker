<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnEventResultsByRaceCreationFailed;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Event\EventResultsByRaceCreationFailedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class EventResultsByRaceCreationFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(EventResultsByRaceCreationFailedEvent $event): void
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
