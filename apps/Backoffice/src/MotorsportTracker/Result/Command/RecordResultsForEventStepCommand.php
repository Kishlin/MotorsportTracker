<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Result\Command;

use Kishlin\Apps\Backoffice\MotorsportTracker\Shared\Command\CommandRequiringSeasonIdTrait;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeResponse;
use Kishlin\Backend\MotorsportTracker\Event\Domain\View\EventStepIdAndDateTimePOPO;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeResponse;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\View\RacerPOPO;
use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\RecordResultsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\ResultDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class RecordResultsForEventStepCommand extends SymfonyCommand
{
    use CommandRequiringSeasonIdTrait;

    public const NAME = 'kishlin:motorsport:result:add';

    private const ARGUMENT_EVENT = 'event';
    private const QUESTION_EVENT = "Please enter a keyword to find the event:\n";

    private const ARGUMENT_TYPE = 'type';
    private const QUESTION_TYPE = "Please enter the type of the event:\n";

    private const QUESTION_RESULT = "Please enter {position} - {points} - {fastest lap} for %s #%d:\n";

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
            ->setDescription('Record results for an event step.')
            ->addSeasonIdArguments()
            ->addArgument(self::ARGUMENT_EVENT, InputArgument::OPTIONAL, 'The event for which to record results')
            ->addArgument(self::ARGUMENT_TYPE, InputArgument::OPTIONAL, 'The type of the event for which to record results')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        try {
            $seasonId  = $this->findSeasonId($input, $output, $ui);
            $eventStep = $this->findEventStepDetails($input, $output, $ui, $seasonId);
            $racers    = $this->findRacersForDateTime($eventStep->dateTime(), $seasonId, $ui);
        } catch (Throwable) {
            return Command::FAILURE;
        }

        /** @var ResultDTO[] $resultsDTO */
        $resultsDTO = [];

        foreach ($racers as $racer) {
            $resultQuestion = sprintf(self::QUESTION_RESULT, $racer->driverName(), $racer->carNumber());

            $result = $this->stringFromPrompt($input, $output, $resultQuestion);

            [$position, $points, $fastestLapTime] = explode(' ', $result);

            $resultsDTO[] = ResultDTO::fromScalars($racer->racerId(), $fastestLapTime, (int) $position, (float) $points);
        }

        try {
            $this->commandBus->execute(RecordResultsCommand::fromScalars($eventStep->eventStepId(), $resultsDTO));
        } catch (Throwable) {
            $ui->error('Failed to record the results.');

            return Command::FAILURE;
        }

        $ui->success('Results recorded.');

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findEventStepDetails(
        InputInterface $input,
        OutputInterface $output,
        SymfonyStyle $ui,
        string $season,
    ): EventStepIdAndDateTimePOPO {
        $keyword = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_EVENT, self::QUESTION_EVENT);
        $type    = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_TYPE, self::QUESTION_TYPE);

        try {
            /** @var SearchEventStepIdAndDateTimeResponse $response */
            $response = $this->queryBus->ask(SearchEventStepIdAndDateTimeQuery::fromScalars($season, $keyword, $type));
        } catch (Throwable $e) {
            $ui->error("Failed to find the event with keyword {$keyword} and type {$type}.");

            throw $e;
        }

        return $response->eventStep();
    }

    /**
     * @throws Throwable
     *
     * @return RacerPOPO[]
     */
    private function findRacersForDateTime(string $dateTime, string $season, SymfonyStyle $ui): array
    {
        try {
            /** @var GetAllRacersForDateTimeResponse $response */
            $response = $this->queryBus->ask(GetAllRacersForDateTimeQuery::fromScalars($dateTime, $season));
        } catch (Throwable $e) {
            $ui->error("Failed to find racers at dateTime {$dateTime}");

            throw $e;
        }

        return $response->racers();
    }
}
