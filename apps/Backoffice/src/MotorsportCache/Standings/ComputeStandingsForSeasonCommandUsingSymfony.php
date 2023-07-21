<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Standings;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\ComputeSeasonStandingsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class ComputeStandingsForSeasonCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:standings:compute';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the championship:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Syncs the events cache.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The name of the championship')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The year of the championship')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $championship = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year         = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);

        try {
            $this->commandBus->execute(ComputeSeasonStandingsCommand::fromScalars($championship, $year));
        } catch (Throwable $e) {
            $ui->error([
                'Failed to compute standings for this season.',
                $e->getMessage(),
            ]);

            return Command::FAILURE;
        }

        $ui->success("Successfully computed standings for {$championship}#{$year}.");

        return Command::SUCCESS;
    }
}
