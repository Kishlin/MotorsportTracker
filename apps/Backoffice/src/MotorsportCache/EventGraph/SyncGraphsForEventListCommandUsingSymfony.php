<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\EventGraph;

use Kishlin\Apps\Backoffice\MotorsportCache\Shared\AbstractEventCommandUsingSymfony;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph\ComputeFastestLapDeltaGraphCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\ComputeHistoriesForEventCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\ComputeLapByLapGraphCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph\ComputePositionChangeGraphCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\ComputeTyreHistoryGraphCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncGraphsForEventListCommandUsingSymfony extends AbstractEventCommandUsingSymfony
{
    protected function commandName(): string
    {
        return 'kishlin:motorsport-cache:graphs:compute';
    }

    protected function commandDescription(): string
    {
        return 'Computes graphs for all events in the list.';
    }

    protected function executeForEvent(SymfonyStyle $ui, string $series, int $year, string $event): void
    {
        $this->commandBus->execute(ComputeHistoriesForEventCommand::fromScalars($event));
        $this->commandBus->execute(ComputeFastestLapDeltaGraphCommand::fromScalars($event));
        $this->commandBus->execute(ComputePositionChangeGraphCommand::fromScalars($event));
        $this->commandBus->execute(ComputeTyreHistoryGraphCommand::fromScalars($event));
        $this->commandBus->execute(ComputeLapByLapGraphCommand::fromScalars($event));
    }

    protected function onError(SymfonyStyle $ui, Throwable $throwable): void
    {
        $ui->error("Failed to compute graphs: {$throwable->getMessage()}");
    }
}
