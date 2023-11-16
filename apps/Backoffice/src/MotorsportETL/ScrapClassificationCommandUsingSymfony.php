<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportETL;

use Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification\ScrapClassificationCommand;
use Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification\ScrapClassificationFailures;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapClassificationCommandUsingSymfony extends CachableScrapCommandUsingSymfony
{
    public const NAME = 'kishlin:motorsport-etl:classification:scrap';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    private const OPTION_EVENT = 'event';

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap standings for a championship season.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The season year')
            ->addOption(self::OPTION_EVENT, null, InputOption::VALUE_OPTIONAL, 'The event name. Leave empty to scrap the whole season.', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);
        $event  = $this->getEventFromCommandOption($input);

        $result = $this->executeApplicationCommand($input, ScrapClassificationCommand::forEvents($series, $year, $event));

        if ($result->isOk()) {
            $ui->success("Finished scrapping standings for {$series} #{$year}.");

            return Command::SUCCESS;
        }

        if (ScrapClassificationFailures::NO_SESSIONS === $result->unwrapFailure()) {
            $ui->error("No sessions found for {$series} #{$year} {$event}.");
        }

        return Command::FAILURE;
    }

    private function getEventFromCommandOption(InputInterface $input): ?string
    {
        $event = $input->getOption(self::OPTION_EVENT);
        if (empty($event)) {
            $event = null;
        }

        assert(null === $event || is_string($event));

        return $event;
    }
}
