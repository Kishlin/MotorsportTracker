<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\ChampionshipCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AddSeasonCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:season:add';

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter a numeric year:\n";

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter a championship id:\n";

    public function __construct(
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new season.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The related championship')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The year of the season')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $year         = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);
        $championship = $this->stringFromArgumentsOrPrompt(
            $input,
            $output,
            self::ARGUMENT_CHAMPIONSHIP,
            self::QUESTION_CHAMPIONSHIP,
        );

        try {
            /** @var SeasonId $uuid */
            $uuid = $this->commandBus->execute(CreateSeasonCommand::fromScalars($championship, $year));
        } catch (ChampionshipCreationFailureException) {
            $ui->error('This season already exists.');

            return Command::FAILURE;
        }

        $ui->text(sprintf("<info>Season Created: %s</info>\n", $uuid));

        return Command::SUCCESS;
    }
}
