<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Standing\Application\SyncAnalyticsAfterScrapping;

use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\UpdateConstructorAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\UpdateDriverAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache\UpdateTeamAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportTask\Standing\Application\SyncStandingsAfterAnalytics\SyncStandingsAfterAnalyticsTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncAnalyticsAfterScrappingTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(SyncAnalyticsAfterScrappingTask $task): void
    {
        $this->commandBus->execute(
            UpdateConstructorAnalyticsCacheCommand::fromScalars(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->commandBus->execute(
            UpdateDriverAnalyticsCacheCommand::fromScalars(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->commandBus->execute(
            UpdateTeamAnalyticsCacheCommand::fromScalars(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->taskBus->execute(
            SyncStandingsAfterAnalyticsTask::forSeasonAndJob(
                $task->series()->value(),
                $task->year()->value(),
                $task->job()->value(),
            ),
        );
    }
}
