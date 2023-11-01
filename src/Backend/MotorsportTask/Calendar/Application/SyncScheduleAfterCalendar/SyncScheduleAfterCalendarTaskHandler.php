<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Calendar\Application\SyncScheduleAfterCalendar;

use Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache\UpdateSeasonScheduleCacheCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncScheduleAfterCalendarTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(SyncScheduleAfterCalendarTask $task): void
    {
        $this->commandBus->execute(
            UpdateSeasonScheduleCacheCommand::fromScalars(
                $task->series()->value(),
                $task->year()->value(),
            ),
        );

        $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));
    }
}
