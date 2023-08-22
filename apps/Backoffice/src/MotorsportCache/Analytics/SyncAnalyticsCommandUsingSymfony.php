<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Analytics;

use Kishlin\Apps\Backoffice\MotorsportCache\Shared\AbstractSeasonCommandUsingSymfony;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\UpdateConstructorAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\UpdateDriverAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache\UpdateTeamAnalyticsCacheCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncAnalyticsCommandUsingSymfony extends AbstractSeasonCommandUsingSymfony
{
    protected function commandName(): string
    {
        return 'kishlin:motorsport-cache:analytics:compute';
    }

    protected function commandDescription(): string
    {
        return 'Computes analytics for a championship season.';
    }

    protected function executeForSeason(SymfonyStyle $ui, InputInterface $input, string $series, int $year): void
    {
        $this->commandBus->execute(UpdateConstructorAnalyticsCacheCommand::fromScalars($series, $year));
        $this->commandBus->execute(UpdateDriverAnalyticsCacheCommand::fromScalars($series, $year));
        $this->commandBus->execute(UpdateTeamAnalyticsCacheCommand::fromScalars($series, $year));

        $ui->success("Finished syncing analytics for {$series} {$year}");
    }

    protected function onError(SymfonyStyle $ui, Throwable $throwable): void
    {
        $ui->error("Failed to compute analytics: {$throwable->getMessage()}");
    }
}
