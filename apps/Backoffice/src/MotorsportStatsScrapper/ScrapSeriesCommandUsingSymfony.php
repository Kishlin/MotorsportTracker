<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportStatsScrapper;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\ScrapSeriesListCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapSeriesCommandUsingSymfony extends CachableScrapCommandUsingSymfony
{
    public const NAME = 'kishlin:motorsport-stats:series:scrap';

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

        $result = $this->executeApplicationCommand($input, ScrapSeriesListCommand::create());

        if ($result->isOk()) {
            $ui->success('Finished scrapping series.');

            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
