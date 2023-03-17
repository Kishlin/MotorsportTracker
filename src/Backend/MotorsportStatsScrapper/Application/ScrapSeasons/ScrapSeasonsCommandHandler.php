<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\CreateSeasonIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;

final class ScrapSeasonsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SeriesGateway $seriesRefGateway,
        private readonly SeasonsGateway $seasonsGateway,
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(ScrapSeasonsCommand $command): void
    {
        $series = $this->seriesRefGateway->findMotorsportStatsUuidForName($command->seriesName());
        if (null === $series) {
            return;
        }

        foreach ($this->seasonsGateway->fetchForChampionship($series->ref())->seasons() as $season) {
            $this->commandBus->execute(
                CreateSeasonIfNotExistsCommand::fromScalars(
                    $series->id(),
                    $season['year'],
                    $season['uuid'],
                ),
            );
        }
    }
}
