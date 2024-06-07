<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncTyreHistoryGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\ComputeTyreHistoryGraphCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class SyncTyreHistoryGraphTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private CommandBus $commandBus,
    ) {}

    public function __invoke(SyncTyreHistoryGraphTask $task): void
    {
        $this->commandBus->execute(
            ComputeTyreHistoryGraphCommand::fromScalars(
                $task->eventId()->value(),
            ),
        );

        $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));
    }
}
