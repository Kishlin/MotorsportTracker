<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Classification\Application\SyncGraphFastestLapDelta;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph\ComputeFastestLapDeltaGraphCommand;
use Kishlin\Backend\MotorsportTask\Classification\Application\SyncGraphPositionChange\SyncGraphPositionChangeTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncGraphFastestLapDeltaTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(SyncGraphFastestLapDeltaTask $task): void
    {
        $this->commandBus->execute(
            ComputeFastestLapDeltaGraphCommand::fromScalars(
                $task->eventId()->value(),
            ),
        );

        $this->taskBus->execute(
            SyncGraphPositionChangeTask::forEventAndJob(
                $task->eventId()->value(),
                $task->job()->value(),
            ),
        );
    }
}
