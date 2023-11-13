<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Standing\Application\SyncStandingsAfterAnalytics;

use Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache\UpdateSeasonStandingsCacheCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncStandingsAfterAnalyticsTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(SyncStandingsAfterAnalyticsTask $task): void
    {
        $this->commandBus->execute(
            UpdateSeasonStandingsCacheCommand::fromScalars(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));
    }
}
