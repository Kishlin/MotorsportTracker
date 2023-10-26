<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTask\Command;

use Kishlin\Backend\MotorsportTask\Job\Application\RecordJob\RecordJobCommand;
use Kishlin\Backend\MotorsportTask\Series\Application\Scrap\ScrapSeriesTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StartSeriesScrapJobCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-task:scrap-series';

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly TaskBus $taskBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap series')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $uuid = $this->commandBus->execute(RecordJobCommand::scrapSeriesJob());

        assert($uuid instanceof UuidValueObject);
        $this->taskBus->execute(ScrapSeriesTask::forJob($uuid->value()));

        return Command::SUCCESS;
    }
}
