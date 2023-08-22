<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Shared;

use Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList\GetSeasonListQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList\GetSeasonListResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

abstract class AbstractSeasonCommandUsingSymfony extends SymfonyCommand
{
    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const OPTION_YEAR = 'year';

    public function __construct(
        protected readonly CommandBus $commandBus,
        protected readonly QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    abstract protected function commandName(): string;

    abstract protected function commandDescription(): string;

    abstract protected function executeForSeason(SymfonyStyle $ui, InputInterface $input, string $series, int $year): void;

    abstract protected function onError(SymfonyStyle $ui, Throwable $throwable): void;

    protected function configure(): void
    {
        $this
            ->setName($this->commandName())
            ->setDescription($this->commandDescription())
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name.')
            ->addOption(self::OPTION_YEAR, null, InputOption::VALUE_OPTIONAL, 'The year filter. Leave empty to sync all seasons.', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year   = $this->getYearFromCommandOption($input);

        try {
            $this->executeForSeries($ui, $input, $series, $year);
        } catch (Throwable $e) {
            $this->onError($ui, $e);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function executeForSeries(SymfonyStyle $ui, InputInterface $input, string $series, ?int $year): void
    {
        if (null !== $year) {
            $this->executeForSeason($ui, $input, $series, $year);

            return;
        }

        $response = $this->queryBus->ask(GetSeasonListQuery::fromScalars($series));
        assert($response instanceof GetSeasonListResponse);

        foreach ($response->yearListDTO()->yearList() as $year) {
            $this->executeForSeason($ui, $input, $series, $year);
        }
    }

    private function getYearFromCommandOption(InputInterface $input): ?int
    {
        $year = $input->getOption(self::OPTION_YEAR);
        if (empty($year)) {
            return null;
        }

        assert(is_numeric($year));

        return (int) $year;
    }
}
