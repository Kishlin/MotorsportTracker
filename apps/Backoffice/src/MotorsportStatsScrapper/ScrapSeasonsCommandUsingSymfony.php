<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportStatsScrapper;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsCommand;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsFailures;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapSeasonsCommandUsingSymfony extends CachableScrapCommandUsingSymfony
{
    public const NAME = 'kishlin:motorsport-stats:seasons:scrap';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap seasons for a championship.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series slug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);

        $result = $this->executeApplicationCommand($input, ScrapSeasonsCommand::forSeries($series));

        if ($result->isOk()) {
            $ui->success("Finished scrapping seasons for {$series}.");

            return Command::SUCCESS;
        }

        if (ScrapSeasonsFailures::SERIES_NOT_FOUND === $result->unwrapFailure()) {
            $ui->warning("Series {$series} not found.");
        }

        return Command::FAILURE;
    }
}
