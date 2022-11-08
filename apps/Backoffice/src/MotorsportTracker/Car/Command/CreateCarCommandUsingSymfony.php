<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Car\Command;

use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\RegisterCarCommand;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonResponse;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamQuery;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class CreateCarCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:car:add';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter a keyword to find the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    private const ARGUMENT_TEAM = 'team';
    private const QUESTION_TEAM = "Please enter a keyword to find the team:\n";

    private const ARGUMENT_NUMBER = 'number';
    private const QUESTION_NUMBER = "Please enter a number for the car:\n";

    public function __construct(
        private QueryBus $queryBus,
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new car.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'A keyword to find the championship')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The year of the season')
            ->addArgument(self::ARGUMENT_TEAM, InputArgument::OPTIONAL, 'A keyword to find the team')
            ->addArgument(self::ARGUMENT_NUMBER, InputArgument::OPTIONAL, 'The number of the car')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        try {
            $seasonId = $this->findSeasonId($input, $output, $ui);
            $teamId   = $this->findTeamId($input, $output, $ui);
        } catch (Throwable) {
            return Command::FAILURE;
        }

        $number = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NUMBER, self::QUESTION_NUMBER);

        try {
            /** @var CarId $uuid */
            $uuid = $this->commandBus->execute(RegisterCarCommand::fromScalars($number, $teamId, $seasonId));
        } catch (Throwable) {
            $ui->error('Failed to register the car with these parameters.');

            return Command::FAILURE;
        }

        $ui->success("Car Created: {$uuid}");

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findSeasonId(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $championship = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year         = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);

        try {
            /** @var SearchSeasonResponse $seasonResponse */
            $seasonResponse = $this->queryBus->ask(SearchSeasonQuery::fromScalars($championship, $year));
        } catch (Throwable $e) {
            $ui->error("Failed to find the season with keyword {$championship} for year {$year}.");

            throw $e;
        }

        return $seasonResponse->seasonId()->value();
    }

    /**
     * @throws Throwable
     */
    private function findTeamId(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $team = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_TEAM, self::QUESTION_TEAM);

        try {
            /** @var SearchTeamResponse $teamResponse */
            $teamResponse = $this->queryBus->ask(SearchTeamQuery::fromScalars($team));
        } catch (Throwable $e) {
            $ui->error("Failed to find the team with keyword {$team}.");

            throw $e;
        }

        return $teamResponse->teamId()->value();
    }
}
