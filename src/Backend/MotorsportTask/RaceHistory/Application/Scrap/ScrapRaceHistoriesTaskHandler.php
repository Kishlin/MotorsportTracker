<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\RaceHistory\Application\Scrap;

use Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory\ScrapRaceHistoryCommand;
use Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncHistories\SyncHistoriesTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class ScrapRaceHistoriesTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {}

    public function __invoke(ScrapRaceHistoriesTask $task): void
    {
        $this->commandBus->execute(
            ScrapRaceHistoryCommand::forEvents(
                $task->series()->value(),
                $task->year()->value(),
                $task->event()->value(),
                cacheMustBeInvalidated: true,
            ),
        );

        $this->taskBus->execute(SyncHistoriesTask::forEventAndJob(
            $task->series()->value(),
            $task->year()->value(),
            $task->event()->value(),
            $task->job()->value(),
        ));
    }
}
