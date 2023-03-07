<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Logging\OnCalendarSync;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Event\CalendarEventCreatedApplicationEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class CalendarEventCreationLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(CalendarEventCreatedApplicationEvent $event): void
    {
        $this->logger->info("[Cache] Calendar Event created for {$event->calendarEventSlug()->value()}");
    }
}
