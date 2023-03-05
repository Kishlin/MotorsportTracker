<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Command\Symfony;

use Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship\SyncChampionshipCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class SyncChampionshipCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-stats:championship:sync';

    private const ARGUMENT_SERIES = 'series';
    private const QUESTION_SERIES = "Please enter a series slug for the sync:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter a year for the sync:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Sync a series season into the service.')
            ->addArgument(self::ARGUMENT_SERIES, InputArgument::OPTIONAL, 'The series slug')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The year of the season')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_SERIES, self::QUESTION_SERIES);
        $year   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);

        $this->commandBus->execute(SyncChampionshipCommand::fromScalars($series, $year));

        $ui->success("Finished syncing {$series} #{$year}");

        return Command::SUCCESS;
    }
}
