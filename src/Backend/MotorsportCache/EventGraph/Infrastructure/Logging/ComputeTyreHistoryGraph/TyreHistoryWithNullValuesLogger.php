<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputeTyreHistoryGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\Event\TyreHistoryWithNullValuesEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class TyreHistoryWithNullValuesLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(TyreHistoryWithNullValuesEvent $event): void
    {
        $this->logger->warning("Tyre history with null values in session {$event->session()}.", $event->series());
    }
}
