<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportStatsScrapper;

use Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList\ScrapSeriesListCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapSeriesCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-stats:series:scrap';

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap all series from motorsport-stats.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $this->commandBus->execute(ScrapSeriesListCommand::create());

        $ui->success('Finished scrapping series.');

        return Command::SUCCESS;
    }
}
