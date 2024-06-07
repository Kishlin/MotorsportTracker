<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Classification\Application\SyncEventResults;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\UpdateEventResultsCacheCommand;
use Kishlin\Backend\MotorsportTask\Classification\Application\SyncGraphFastestLapDelta\SyncGraphFastestLapDeltaTask;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\MotorsportTask\Shared\Application\EventIdGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncEventResultsTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private EventIdGateway $eventIdGateway,
        private CommandBus $commandBus,
        private TaskBus $taskBus,
    ) {}

    public function __invoke(SyncEventResultsTask $task): void
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

        $this->commandBus->execute(UpdateEventResultsCacheCommand::fromScalars($eventId));

        $this->taskBus->execute(SyncGraphFastestLapDeltaTask::forEventAndJob($eventId, $task->job()->value()));
    }
}
