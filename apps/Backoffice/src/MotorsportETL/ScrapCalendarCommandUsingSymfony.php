<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportETL;

use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\ScrapCalendarCommand;
use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\ScrapCalendarFailures;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapCalendarCommandUsingSymfony extends CachableScrapCommandUsingSymfony
{
    public const NAME = 'kishlin:motorsport-etl:calendar:scrap';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap calendar for a championship season.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The season year')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);

        $result = $this->executeApplicationCommand($input, ScrapCalendarCommand::forSeason($series, $year));

        if ($result->isOk()) {
            $ui->success("Finished scrapping calendar for {$series} #{$year}.");

            return Command::SUCCESS;
        }

        if (ScrapCalendarFailures::SEASON_NOT_FOUND === $result->unwrapFailure()) {
            $ui->error("Season {$series} #{$year} not found.");
        }

        return Command::FAILURE;
    }
}
