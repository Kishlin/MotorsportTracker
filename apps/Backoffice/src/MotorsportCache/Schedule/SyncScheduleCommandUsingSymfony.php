<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Schedule;

use Kishlin\Apps\Backoffice\MotorsportCache\Shared\AbstractSeasonCommandUsingSymfony;
use Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache\UpdateSeasonScheduleCacheCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncScheduleCommandUsingSymfony extends AbstractSeasonCommandUsingSymfony
{
    protected function commandName(): string
    {
        return 'kishlin:motorsport-cache:schedule:compute';
    }

    protected function commandDescription(): string
    {
        return 'Computes calendar for a championship season.';
    }

    protected function executeForSeason(SymfonyStyle $ui, InputInterface $input, string $series, int $year): void
    {
        $this->commandBus->execute(UpdateSeasonScheduleCacheCommand::fromScalars($series, $year));

        $ui->success("Finished syncing schedule for {$series} {$year}");
    }

    protected function onError(SymfonyStyle $ui, Throwable $throwable): void
    {
        $ui->error("Failed to compute schedule: {$throwable->getMessage()}");
    }
}
