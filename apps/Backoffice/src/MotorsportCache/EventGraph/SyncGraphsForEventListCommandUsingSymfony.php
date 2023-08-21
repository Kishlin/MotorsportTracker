<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\EventGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph\ComputeFastestLapDeltaGraphCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\ComputeHistoriesForEventCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\ComputeLapByLapGraphCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph\ComputePositionChangeGraphCommand;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\ComputeTyreHistoryGraphCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList\GetSeasonEventIdListQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList\GetSeasonEventIdListResponse;
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

final class SyncGraphsForEventListCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:graphs:compute';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    private const OPTION_EVENT = 'event';

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Computes graphs for all events in the list.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name.')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The season year.')
            ->addOption(self::OPTION_EVENT, null, InputOption::VALUE_OPTIONAL, 'The event filter. Leave empty to sync the whole season.', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series      = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year        = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);
        $eventFilter = $this->getEventFilterFromCommandOption($input);

        $ui->info("Syncing results for {$series} {$year}");

        try {
            $response = $this->queryBus->ask(GetSeasonEventIdListQuery::fromScalars($series, $year, $eventFilter));
            assert($response instanceof GetSeasonEventIdListResponse);
        } catch (Throwable $e) {
            $ui->error("Failed to compute graphs: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $ui->progressStart(count($response->eventIdList()->idList()));

        foreach ($response->eventIdList()->idList() as $id) {
            $ui->progressAdvance();

            $this->commandBus->execute(ComputeHistoriesForEventCommand::fromScalars($id));
            $this->commandBus->execute(ComputeFastestLapDeltaGraphCommand::fromScalars($id));
            $this->commandBus->execute(ComputePositionChangeGraphCommand::fromScalars($id));
            $this->commandBus->execute(ComputeTyreHistoryGraphCommand::fromScalars($id));
            $this->commandBus->execute(ComputeLapByLapGraphCommand::fromScalars($id));
        }

        $ui->progressFinish();

        $ui->success("Finished syncing graphs for {$series} {$year}");

        return Command::SUCCESS;
    }

    private function getEventFilterFromCommandOption(InputInterface $input): ?string
    {
        $event = $input->getOption(self::OPTION_EVENT);
        if (empty($event)) {
            $event = null;
        }

        assert(null === $event || is_string($event));

        return $event;
    }
}
