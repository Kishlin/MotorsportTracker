<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Seasons\Application\Scrap;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
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
        $this->commandBus->execute(
            ScrapSeasonsCommand::forSeries($task->series()->value(), cacheMustBeInvalidated: true),
        );

        $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));
    }
}
