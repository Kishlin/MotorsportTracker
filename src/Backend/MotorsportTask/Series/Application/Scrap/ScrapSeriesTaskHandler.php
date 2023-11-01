<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Series\Application\Scrap;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\ScrapSeriesListCommand;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskHandler;

final readonly class ScrapSeriesTaskHandler implements TaskHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(ScrapSeriesTask $task): void
    {
        $this->commandBus->execute(ScrapSeriesListCommand::create());

        $this->eventDispatcher->dispatch(JobFinishedEvent::forJob($task->job()->value()));
    }
}
