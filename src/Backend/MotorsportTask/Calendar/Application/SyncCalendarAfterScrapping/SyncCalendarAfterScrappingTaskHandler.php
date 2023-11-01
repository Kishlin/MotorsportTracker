<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Calendar\Application\SyncCalendarAfterScrapping;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\SyncCalendarEventsCommand;
use Kishlin\Backend\MotorsportTask\Calendar\Application\SyncScheduleAfterCalendar\SyncScheduleAfterCalendarTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncCalendarAfterScrappingTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(SyncCalendarAfterScrappingTask $task): void
    {
        $this->commandBus->execute(
            SyncCalendarEventsCommand::fromScalars(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->taskBus->execute(
            SyncScheduleAfterCalendarTask::forSeriesAndJob(
                $task->series()->value(),
                $task->year()->value(),
                $task->job()->value(),
            ),
        );
    }
}
