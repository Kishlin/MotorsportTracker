<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\DeprecatedLapByLapGraphDeletedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class OnDeprecatedLapByLapGraphDeletedLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(DeprecatedLapByLapGraphDeletedEvent $event): void
    {
        $this->logger->info("Deleted deprecated lap by lap graph for event {$event->event()}");
    }
}
