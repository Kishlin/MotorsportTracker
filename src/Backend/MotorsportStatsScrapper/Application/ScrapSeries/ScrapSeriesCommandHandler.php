<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeries;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;

final class ScrapSeriesCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly SeriesGateway $gateway,
    ) {
    }

    public function __invoke(ScrapSeriesCommand $command): void
    {
        foreach ($this->gateway->fetch()->series() as $series) {
            $this->commandBus->execute(
                CreateChampionshipIfNotExistsCommand::fromScalars(
                    $series['name'],
                    $series['shortName'],
                    $series['shortCode'],
                    $series['uuid'],
                ),
            );
        }
    }
}
