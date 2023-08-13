<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputeTyreHistoryGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\Event\MismatchingTyreAndPitHistoriesEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class MismatchingTyreAndPitHistoriesLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(MismatchingTyreAndPitHistoriesEvent $event): void
    {
        $this->logger->warning("Mismatching tyre and pit histories in session {$event->session()}.", $event->series());
    }
}
