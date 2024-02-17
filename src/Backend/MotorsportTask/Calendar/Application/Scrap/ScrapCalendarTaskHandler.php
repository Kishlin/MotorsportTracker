<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Calendar\Application\Scrap;

use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\ScrapCalendarCommand;
use Kishlin\Backend\MotorsportTask\Calendar\Application\SyncCalendarAfterScrapping\SyncCalendarAfterScrappingTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class ScrapCalendarTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(ScrapCalendarTask $task): void
    {
        $this->commandBus->execute(
            ScrapCalendarCommand::forSeason(
                $task->series()->value(),
                $task->year()->value(),
                cacheMustBeInvalidated: true,
            ),
        );

        $this->taskBus->execute(
            SyncCalendarAfterScrappingTask::forSeriesAndJob(
                $task->series()->value(),
                $task->year()->value(),
                $task->job()->value(),
            ),
        );
    }
}
