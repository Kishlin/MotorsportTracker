<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Standings;

use Kishlin\Apps\Backoffice\MotorsportCache\Shared\AbstractSeasonCommandUsingSymfony;
use Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache\UpdateSeasonStandingsCacheCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncSeasonStandingsCommandUsingSymfony extends AbstractSeasonCommandUsingSymfony
{
    protected function commandName(): string
    {
        return 'kishlin:motorsport-cache:standings:compute';
    }

    protected function commandDescription(): string
    {
        return 'Computes standings for a championship season.';
    }

    protected function executeForSeason(SymfonyStyle $ui, InputInterface $input, string $series, int $year): void
    {
        $this->commandBus->execute(UpdateSeasonStandingsCacheCommand::fromScalars($series, $year));

        $ui->success("Finished syncing standings for {$series} {$year}");
    }

    protected function onError(SymfonyStyle $ui, Throwable $throwable): void
    {
        $ui->error("Failed to compute standings: {$throwable->getMessage()}");
    }
}
