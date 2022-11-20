<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Result;

use Behat\Gherkin\Node\TableNode;
use Kishlin\Apps\Backoffice\MotorsportTracker\Result\Command\RecordResultsForEventStepCommand;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Helper\Results\ResultsHolderInterface;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Helper\Results\ResultsQuestionHelper;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;

final class ResultContext extends BackofficeContext implements ResultsHolderInterface
{
    private const CAR_NUMBER_QUERY = <<<'SQL'
SELECT c.number as number
FROM cars c
LEFT JOIN racers r on c.id = r.car
WHERE r.id = :racerId
SQL;

    private const DATA_FROM_EVENT_STEP_ID_QUERY = <<<'SQL'
SELECT c.slug as championship, s.year as year, e.label as event, st.label as type
FROM event_steps es
LEFT JOIN events e on es.event = e.id
LEFT JOIN step_types st on st.id = es.type
LEFT JOIN seasons s on e.season = s.id
LEFT JOIN championships c on s.championship = c.id
WHERE es.id = :eventStepId
SQL;

    private ?int $commandStatus = null;

    /** @var array<string, array{points: float, position: int}> */
    private array $results;

    /**
     * @When a client records the following results for eventStep :eventStep:
     */
    public function aClientRecordsTheFollowingResults(string $eventStep, TableNode $resultsTable): void
    {
        $this->commandStatus = null;

        /** @var array<string, array{position: int, points: float}> $results */
        $results = [];

        /** @var array<array{racer: string, position: int, points: float}> $resultTable */
        $resultTable = $resultsTable;

        foreach ($resultTable as $resultData) {
            $racerId = self::database()->fixtureId("motorsport.racer.racer.{$this->format($resultData['racer'])}");

            /** @var array{number: string} $carNumberResult */
            $carNumberResult = self::database()->fetchAssociative(self::CAR_NUMBER_QUERY, ['racerId' => $racerId]);

            $results[$carNumberResult['number']] = [
                'points'   => $resultData['points'],
                'position' => $resultData['position'],
            ];

            $this->results = $results;
        }

        $eventStepId = self::database()->fixtureId("motorsport.event.eventStep.{$this->format($eventStep)}");

        /** @var array{championship: string, year: int, event: string, type: string} $result */
        $result = self::database()->fetchAssociative(self::DATA_FROM_EVENT_STEP_ID_QUERY, ['eventStepId' => $eventStepId]);

        $command = self::application()->find(RecordResultsForEventStepCommand::NAME);

        self::prepareToEnterResultsForRacers($command);

        $commandTester = new CommandTester($command);

        $commandTester->execute($result);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the results are recorded$/
     */
    public function theResultsAreRecorded(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);
    }

    /**
     * @Then /^the driver's standings are now$/
     */
    public function theDriversStandingsAreNow(TableNode $table): void
    {
        /** @var array<array{driver: string, event: string, points: float}> $expectedStandings */
        $expectedStandings = $table;

        foreach ($expectedStandings as $expectedStanding) {
            $driverId = self::database()->fixtureId("motorsport.driver.driver.{$this->format($expectedStanding['driver'])}");
            $eventId  = self::database()->fixtureId("motorsport.event.event.{$this->format($expectedStanding['event'])}");

            $actualResult = self::database()->fetchOne(
                'SELECT points from driver_standings where event = :event and driver = :driver',
                ['event' => $eventId, 'driver' => $driverId],
            );

            Assert::assertSame($expectedStanding['points'], $actualResult);
        }
    }

    /**
     * @Then /^the team's standings are now$/
     */
    public function theTeamsStandingsAreNow(TableNode $table): void
    {
        /** @var array<array{team: string, event: string, points: float}> $expectedStandings */
        $expectedStandings = $table;

        foreach ($expectedStandings as $expectedStanding) {
            $eventId = self::database()->fixtureId("motorsport.event.event.{$this->format($expectedStanding['event'])}");
            $teamId  = self::database()->fixtureId("motorsport.team.team.{$this->format($expectedStanding['team'])}");

            $actualResult = self::database()->fetchOne(
                'SELECT points from team_standings where event = :event and team = :team',
                ['event' => $eventId, 'team' => $teamId],
            );

            Assert::assertSame($expectedStanding['points'], $actualResult);
        }
    }

    /**
     * @return array{points: float, position: int}
     */
    public function getResultsForCar(string $carNumber): array
    {
        return $this->results[$carNumber];
    }

    private function prepareToEnterResultsForRacers(Command $command): void
    {
        $command->setHelperSet(new HelperSet(['question' => new ResultsQuestionHelper($this)]));
    }
}
