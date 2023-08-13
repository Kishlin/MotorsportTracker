<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputeFastestLapDeltaGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph\EmptyFastestLapDataEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class OnEmptyFastestLapDeltaLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(EmptyFastestLapDataEvent $event): void
    {
        $this->logger->notice("Found Fastest Lap Delta to be empty for session {$event->session()}");
    }
}
