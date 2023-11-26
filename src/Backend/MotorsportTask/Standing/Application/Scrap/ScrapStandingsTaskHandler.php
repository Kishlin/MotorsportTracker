<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Standing\Application\Scrap;

use Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings\ScrapStandingsCommand;
use Kishlin\Backend\MotorsportTask\Standing\Application\SpotMissingConstructorTeamRelations\SpotMissingConstructorTeamRelationsTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class ScrapStandingsTaskHandler implements TaskHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(ScrapStandingsTask $task): void
    {
        $this->commandBus->execute(
            ScrapStandingsCommand::forSeason(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->taskBus->execute(
            SpotMissingConstructorTeamRelationsTask::forSeasonAndJob(
                $task->series()->value(),
                $task->year()->value(),
                $task->job()->value(),
            ),
        );
    }
}
