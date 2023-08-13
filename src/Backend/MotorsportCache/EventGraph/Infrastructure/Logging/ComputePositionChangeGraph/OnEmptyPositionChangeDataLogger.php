<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputePositionChangeGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph\EmptyPositionChangeDataEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class OnEmptyPositionChangeDataLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(EmptyPositionChangeDataEvent $event): void
    {
        $this->logger->notice("Found Position Change to be empty for session {$event->session()}");
    }
}
