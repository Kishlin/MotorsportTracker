<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\StandingDataDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\StandingDataReaderUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\StandingDataReaderUsingDoctrine
 */
final class StandingDataReaderUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItGetsDataForAnEventWithOneStep(): void
    {
        self::loadFixtures(
            'motorsport.result.result.verstappenAtDutchGP2022Race',
            'motorsport.result.result.perezAtDutchGP2022Race',
        );

        $reader = new StandingDataReaderUsingDoctrine(self::entityManager());
        $data   = $reader->findStandingDataForEvent(
            self::fixtureId('motorsport.event.event.dutchGrandPrix2022'),
        );

        $expected = [
            $this->standingDataDTO('maxVerstappen', 'redBullRacing', 26.0),
            $this->standingDataDTO('sergioPerez', 'redBullRacing', 10.0),
        ];

        self::assertEqualsCanonicalizing($expected, $data);
    }

    /**
     * @throws Exception
     */
    public function testItGetsDataForAnEventWithTwoSteps(): void
    {
        self::loadFixtures(
            'motorsport.result.result.verstappenAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.perezAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.verstappenAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.perezAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022Race',
        );

        $reader = new StandingDataReaderUsingDoctrine(self::entityManager());
        $data   = $reader->findStandingDataForEvent(
            self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022'),
        );

        $expected = [
            $this->standingDataDTO('maxVerstappen', 'redBullRacing', 34.0),
            $this->standingDataDTO('sergioPerez', 'redBullRacing', 24.0),
            $this->standingDataDTO('lewisHamilton', 'mercedes', 0.0),
        ];

        self::assertEqualsCanonicalizing($expected, $data);
    }

    /**
     * @throws Exception
     */
    public function testItGetsDataForTwoSuccessiveEvents(): void
    {
        self::loadFixtures(
            'motorsport.result.result.verstappenAtAustralianGP2022Race',
            'motorsport.result.result.hamiltonAtAustralianGP2022Race',
            'motorsport.result.result.perezAtAustralianGP2022Race',
            'motorsport.result.result.verstappenAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.perezAtEmiliaRomagnaGP2022Race',
        );

        $reader = new StandingDataReaderUsingDoctrine(self::entityManager());
        $data   = $reader->findStandingDataForEvent(
            self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022'),
        );

        $expected = [
            $this->standingDataDTO('maxVerstappen', 'redBullRacing', 26.0),
            $this->standingDataDTO('sergioPerez', 'redBullRacing', 36.0),
            $this->standingDataDTO('lewisHamilton', 'mercedes', 12.0),
        ];

        self::assertEqualsCanonicalizing($expected, $data);
    }

    /**
     * @throws Exception
     */
    public function testItGetsDataForSuccessiveEventsWithMultipleSteps(): void
    {
        self::loadFixtures(
            'motorsport.result.result.verstappenAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.perezAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.verstappenAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.perezAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.verstappenAtAustralianGP2022Race',
            'motorsport.result.result.hamiltonAtAustralianGP2022Race',
            'motorsport.result.result.perezAtAustralianGP2022Race',
        );

        $reader = new StandingDataReaderUsingDoctrine(self::entityManager());
        $data   = $reader->findStandingDataForEvent(
            self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022'),
        );

        $expected = [
            $this->standingDataDTO('maxVerstappen', 'redBullRacing', 34.0),
            $this->standingDataDTO('sergioPerez', 'redBullRacing', 42.0),
            $this->standingDataDTO('lewisHamilton', 'mercedes', 12.0),
        ];

        self::assertEqualsCanonicalizing($expected, $data);
    }

    /**
     * @throws Exception
     */
    public function testItHandlesDriversWhoAreMissingDataForSomeEvents(): void
    {
        self::loadFixtures(
            'motorsport.result.result.verstappenAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022SprintQualifying',
            'motorsport.result.result.hamiltonAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.perezAtEmiliaRomagnaGP2022Race',
            'motorsport.result.result.verstappenAtAustralianGP2022Race',
            'motorsport.result.result.hamiltonAtAustralianGP2022Race',
            'motorsport.result.result.perezAtAustralianGP2022Race',
        );

        $reader = new StandingDataReaderUsingDoctrine(self::entityManager());
        $data   = $reader->findStandingDataForEvent(
            self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022'),
        );

        $expected = [
            $this->standingDataDTO('maxVerstappen', 'redBullRacing', 8.0),
            $this->standingDataDTO('sergioPerez', 'redBullRacing', 36.0),
            $this->standingDataDTO('lewisHamilton', 'mercedes', 12.0),
        ];

        self::assertEqualsCanonicalizing($expected, $data);
    }

    private function standingDataDTO(string $driver, string $team, float $pointsUntilEvent): StandingDataDTO
    {
        return StandingDataDTO::fromScalars(
            self::fixtureId("motorsport.driver.driver.{$driver}"),
            self::fixtureId("motorsport.team.team.{$team}"),
            $pointsUntilEvent,
        );
    }
}
