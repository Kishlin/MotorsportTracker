<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Classification\Application\Scrap;

use Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification\ScrapClassificationCommand;
use Kishlin\Backend\MotorsportTask\Classification\Application\SyncEventResults\SyncEventResultsTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class ScrapClassificationsTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(ScrapClassificationsTask $task): void
    {
        $this->commandBus->execute(
            ScrapClassificationCommand::forEvents(
                $task->series()->value(),
                $task->year()->value(),
                $task->event()->value(),
            ),
        );

        $this->taskBus->execute(
            SyncEventResultsTask::forEventAndJob(
                $task->series()->value(),
                $task->year()->value(),
                $task->event()->value(),
                $task->job()->value(),
            ),
        );
    }
}