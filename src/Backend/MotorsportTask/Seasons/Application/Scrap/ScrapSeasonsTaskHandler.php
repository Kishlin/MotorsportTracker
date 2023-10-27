<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Seasons\Application\Scrap;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobStartedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class ScrapSeasonsTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(ScrapSeasonsTask $task): void
    {
        $event = JobStartedEvent::forJob($task->job()->value());
        $this->eventDispatcher->dispatch($event);

        if (false === $event->isClearToContinue()) {
            return;
        }

        $this->commandBus->execute(ScrapSeasonsCommand::forSeries($task->series()->value()));

        $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));
    }
}
