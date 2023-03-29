<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnEventResultsByRaceCreationRequested;

use Kishlin\Backend\MotorsportCache\Result\Application\CreateEventResultsByRaceOnClassificationCreation\EventResultsByRaceCreationRequestedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class EventResultsByRaceCreationRequestLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(EventResultsByRaceCreationRequestedEvent $event): void
    {
        $this->logger->debug("EventResultsByRace creation requested for {$event->eventId()}.");
    }
}
