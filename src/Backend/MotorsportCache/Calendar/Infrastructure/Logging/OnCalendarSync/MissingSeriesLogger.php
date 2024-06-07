<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Logging\OnCalendarSync;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event\SeriesIsMissingApplicationEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class MissingSeriesLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(SeriesIsMissingApplicationEvent $event): void
    {
        $this->logger->warning("Failed to sync calendar event for missing series {$event->seriesSlug()}");
    }
}
