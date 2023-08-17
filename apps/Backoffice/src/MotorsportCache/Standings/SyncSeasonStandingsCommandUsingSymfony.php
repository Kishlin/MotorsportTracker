<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Standings;

use Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache\UpdateSeasonStandingsCacheCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncSeasonStandingsCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:standings:compute';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Computes standings for a championship season.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name.')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The season year.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);

        try {
            $this->commandBus->execute(UpdateSeasonStandingsCacheCommand::fromScalars($series, $year));
        } catch (Throwable $e) {
            $ui->error("Failed to compute standings: {$e->getMessage()}");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
