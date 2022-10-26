<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\ChampionshipCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ChampionshipPOPO;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsResponse;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
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

    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new season.')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The year of the season')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        /** @var ViewAllChampionshipsResponse $championshipsResponse */
        $championshipsResponse = $this->queryBus->ask(new ViewAllChampionshipsQuery());

        if (empty($championshipsResponse->championships())) {
            $ui->warning('There are no championship available. Create one first.');

            return Command::SUCCESS;
        }

        $selectedChampionshipId = $this->selectAChampionshipId($input, $output, $championshipsResponse);

        $year = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);

        try {
            /** @var SeasonId $uuid */
            $uuid = $this->commandBus->execute(CreateSeasonCommand::fromScalars($selectedChampionshipId, $year));
        } catch (ChampionshipCreationFailureException) {
            $ui->error('This season already exists.');

            return Command::FAILURE;
        }

        $ui->text(sprintf("<info>Season Created: %s</info>\n", $uuid));

        return Command::SUCCESS;
    }

    private function selectAChampionshipId(
        InputInterface $input,
        OutputInterface $output,
        ViewAllChampionshipsResponse $championshipsResponse,
    ): string {
        /** @var ChampionshipPOPO $selectedChampionship */
        $selectedChampionship = $this->selectItemInList(
            $input,
            $output,
            'Please select a championship.',
            $championshipsResponse->championships(),
            'Championship %s is invalid.',
        );

        return $selectedChampionship->id();
    }
}
