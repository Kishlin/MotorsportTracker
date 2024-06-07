<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncHistories;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\ComputeHistoriesForEventCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncLapByLapGraph\SyncLapByLapGraphTask;
use Kishlin\Backend\MotorsportTask\Shared\Application\EventIdGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncHistoriesTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private EventIdGateway $eventIdGateway,
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {}

    public function __invoke(SyncHistoriesTask $task): void
    {
        $eventId = $this->eventIdGateway->findEventId(
            $task->series()->value(),
            $task->year()->value(),
            $task->event()->value(),
        );

        if (null === $eventId) {
            $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));

            return;
        }

        $this->commandBus->execute(
            ComputeHistoriesForEventCommand::fromScalars($eventId),
        );

        $this->taskBus->execute(
            SyncLapByLapGraphTask::forEventAndJob(
                $eventId,
                $task->job()->value(),
            ),
        );
    }
}
