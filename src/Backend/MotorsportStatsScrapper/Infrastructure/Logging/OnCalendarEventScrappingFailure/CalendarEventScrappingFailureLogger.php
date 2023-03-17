<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnCalendarEventScrappingFailure;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\CalendarEventScrappingFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class CalendarEventScrappingFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(CalendarEventScrappingFailureEvent $event): void
    {
        $eventData = $event->event();

        $this->logger->error("Failed to scrap event {$eventData['name']}.", $eventData);
    }
}
