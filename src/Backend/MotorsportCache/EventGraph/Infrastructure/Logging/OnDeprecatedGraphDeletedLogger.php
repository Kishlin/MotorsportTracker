<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\DeprecatedGraphDeletedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class OnDeprecatedGraphDeletedLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(DeprecatedGraphDeletedEvent $event): void
    {
        $this->logger->info("Deleted deprecated {$event->type()->value} graph for event {$event->event()}");
    }
}
