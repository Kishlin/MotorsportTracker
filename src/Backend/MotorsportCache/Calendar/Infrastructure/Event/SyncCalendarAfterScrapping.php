<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\SyncCalendarEventsCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\CalendarEventScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class SyncCalendarAfterScrapping implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(CalendarEventScrappingSuccessEvent $event): void
    {
        $this->logger->notice(
            "Sync calendar events after scrapping for {$event->championship()} {$event->year()}",
        );

        $this->commandBus->execute(
            SyncCalendarEventsCommand::fromScalars($event->championship(), $event->year()),
        );
    }
}
