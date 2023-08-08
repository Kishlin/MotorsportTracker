<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Schedule\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache\UpdateSeasonScheduleCacheCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\CalendarEventScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class SyncSeasonScheduleAfterScrapping implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(CalendarEventScrappingSuccessEvent $event): void
    {
        $this->logger->notice(
            "Update season schedule cache after scrapping for {$event->championship()} {$event->year()}",
        );

        $this->commandBus->execute(
            UpdateSeasonScheduleCacheCommand::fromScalars($event->championship(), $event->year()),
        );
    }
}
