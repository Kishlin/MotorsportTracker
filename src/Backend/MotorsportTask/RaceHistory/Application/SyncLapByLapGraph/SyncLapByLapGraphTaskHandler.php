<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncLapByLapGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\ComputeLapByLapGraphCommand;
use Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncTyreHistoryGraph\SyncTyreHistoryGraphTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncLapByLapGraphTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {}

    public function __invoke(SyncLapByLapGraphTask $task): void
    {
        $this->commandBus->execute(
            ComputeLapByLapGraphCommand::fromScalars($task->eventId()->value()),
        );

        $this->taskBus->execute(
            SyncTyreHistoryGraphTask::forEventAndJob(
                $task->eventId()->value(),
                $task->job()->value(),
            ),
        );
    }
}
