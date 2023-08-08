<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache\UpdateSeasonStandingsCacheCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\StandingsScrappedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class SyncStandingsCacheAfterScrapping implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(StandingsScrappedEvent $event): void
    {
        $this->logger->notice(
            "Updating cache after scrapping of standings for {$event->championshipName()} {$event->year()}.",
        );

        $this->commandBus->execute(
            UpdateSeasonStandingsCacheCommand::fromScalars($event->championshipName(), $event->year()),
        );
    }
}
