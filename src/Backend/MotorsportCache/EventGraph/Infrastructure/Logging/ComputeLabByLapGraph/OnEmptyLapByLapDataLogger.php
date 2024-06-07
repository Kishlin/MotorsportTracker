<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputeLabByLapGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Event\EmptyLapByLapDataEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class OnEmptyLapByLapDataLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(EmptyLapByLapDataEvent $event): void
    {
        $this->logger->notice("Found Lap by Lap Data to be empty for session {$event->session()}");
    }
}
