<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnNoRacesToCompute;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Event\NoSessionsToComputeEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class NoSessionsToComputeLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(NoSessionsToComputeEvent $event): void
    {
        $this->logger->notice("There are no sessions to compute for event {$event->eventId()}");
    }
}
