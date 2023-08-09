<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache\UpdateTeamAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\StandingsScrappedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class UpdateCacheOnTeamStandingsScrapped implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(StandingsScrappedEvent $event): void
    {
        $this->logger->notice(
            "Updating team analytics cache after scrapping of standings for {$event->championshipName()} {$event->year()}.",
        );

        $this->commandBus->execute(
            UpdateTeamAnalyticsCacheCommand::fromScalars($event->championshipName(), $event->year()),
        );
    }
}
