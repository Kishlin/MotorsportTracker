<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\DriverStandingsForSeasonRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\DriverStandingsForSeasonRepositoryUsingDoctrine
 */
final class DriverStandingsForSeasonRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyViewWhenThereIsNoData(): void
    {
        $repository = new DriverStandingsForSeasonRepositoryUsingDoctrine(self::entityManager());

        self::assertEmpty($repository->view('season')->toArray());
    }

    /**
     * @throws Exception
     */
    public function testItCanViewStandings(): void
    {
        self::loadFixtures(
            'motorsport.standing.driverStanding.verstappenAfterAustralianGP2022',
            'motorsport.standing.driverStanding.perezAfterAustralianGP2022',
            'motorsport.standing.driverStanding.hamiltonAfterAustralianGP2022',
            'motorsport.standing.driverStanding.verstappenAfterEmiliaRomagnaGP2022',
            'motorsport.standing.driverStanding.perezAfterEmiliaRomagnaGP2022',
            'motorsport.standing.driverStanding.hamiltonAfterEmiliaRomagnaGP2022',
        );

        $repository = new DriverStandingsForSeasonRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view(self::fixtureId('motorsport.championship.season.formulaOne2022'))->toArray();

        $expected = [
            2 => [
                self::fixtureId('motorsport.driver.driver.maxVerstappen') => 0,
                self::fixtureId('motorsport.driver.driver.sergioPerez')   => 18,
                self::fixtureId('motorsport.driver.driver.lewisHamilton') => 12,
            ],
            3 => [
                self::fixtureId('motorsport.driver.driver.maxVerstappen') => 34,
                self::fixtureId('motorsport.driver.driver.sergioPerez')   => 42,
                self::fixtureId('motorsport.driver.driver.lewisHamilton') => 12,
            ],
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
