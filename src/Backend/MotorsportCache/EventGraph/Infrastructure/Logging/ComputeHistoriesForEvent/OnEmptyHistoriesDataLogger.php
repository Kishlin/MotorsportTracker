<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputeHistoriesForEvent;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\EmptyHistoriesDataEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class OnEmptyHistoriesDataLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(EmptyHistoriesDataEvent $event): void
    {
        $this->logger->notice("Found Histories to be empty for session {$event->session()}");
    }
}
