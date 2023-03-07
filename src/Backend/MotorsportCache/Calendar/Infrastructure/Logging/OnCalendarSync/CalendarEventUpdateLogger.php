<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Logging\OnCalendarSync;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event\CalendarEventUpdatedApplicationEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class CalendarEventUpdateLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(CalendarEventUpdatedApplicationEvent $event): void
    {
        $this->logger->info("[Cache] Calendar Event updated for {$event->calendarEventSlug()->value()}");
    }
}
