<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Logging\OnNoRacesToCompute;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Event\NoSessionsToComputeEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class NoRacesToComputeLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(NoSessionsToComputeEvent $event): void
    {
        $this->logger->notice("There are no races to compute for event {$event->eventId()}");
    }
}
