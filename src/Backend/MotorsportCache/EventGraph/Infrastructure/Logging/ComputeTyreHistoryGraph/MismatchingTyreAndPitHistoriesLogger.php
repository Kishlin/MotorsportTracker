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
    ) {}

    public function __invoke(MismatchingTyreAndPitHistoriesEvent $event): void
    {
        if ($event->skipping()) {
            $this->logger->error("Skipping mismatching tyre and pit histories for {$event->session()}", $event->series());

            return;
        }

        $this->logger->warning("Mismatching tyre and pit histories in session {$event->session()}", $event->series());
    }
}
