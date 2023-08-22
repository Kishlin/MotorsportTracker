<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Calendar;

use Kishlin\Apps\Backoffice\MotorsportCache\Shared\AbstractSeasonCommandUsingSymfony;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\SyncCalendarEventsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncCalendarEventsCommandUsingSymfony extends AbstractSeasonCommandUsingSymfony
{
    protected function commandName(): string
    {
        return 'kishlin:motorsport-cache:calendar:compute';
    }

    protected function commandDescription(): string
    {
        return 'Computes calendar for a championship season.';
    }

    protected function executeForSeason(SymfonyStyle $ui, InputInterface $input, string $series, int $year): void
    {
        $this->commandBus->execute(SyncCalendarEventsCommand::fromScalars($series, $year));

        $ui->success("Finished syncing calendar for {$series} {$year}");
    }

    protected function onError(SymfonyStyle $ui, Throwable $throwable): void
    {
        $ui->error("Failed to compute calendar: {$throwable->getMessage()}");
    }
}
