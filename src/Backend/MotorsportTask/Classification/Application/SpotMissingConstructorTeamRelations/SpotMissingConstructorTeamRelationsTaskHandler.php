<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Classification\Application\SpotMissingConstructorTeamRelations;

use Kishlin\Backend\MotorsportETL\DataFix\Application\FixMissingConstructorTeams\FixMissingConstructorTeamsCommand;
use Kishlin\Backend\MotorsportTask\Classification\Application\SyncEventResults\SyncEventResultsTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SpotMissingConstructorTeamRelationsTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {}

    public function __invoke(SpotMissingConstructorTeamRelationsTask $task): void
    {
        $this->commandBus->execute(
            FixMissingConstructorTeamsCommand::create(),
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
