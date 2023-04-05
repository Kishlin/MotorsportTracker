<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Logging\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Event\DidNotDuplicateEventCachedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class EventCachedDuplicationPreventionLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(DidNotDuplicateEventCachedEvent $event): void
    {
        $this->logger->info(
            sprintf(
                'Did not duplicate cache for event %s #%d - %s.',
                $event->championship(),
                $event->year(),
                $event->event(),
            ),
        );
    }
}
