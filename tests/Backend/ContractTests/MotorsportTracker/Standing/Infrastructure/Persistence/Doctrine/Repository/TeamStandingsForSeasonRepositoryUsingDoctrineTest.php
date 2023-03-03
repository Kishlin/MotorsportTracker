<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\TeamStandingsForSeasonRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\TeamStandingsForSeasonRepositoryUsingDoctrine
 */
final class TeamStandingsForSeasonRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyViewWhenThereIsNoData(): void
    {
        $repository = new TeamStandingsForSeasonRepositoryUsingDoctrine(self::entityManager());

        self::assertEmpty($repository->view('season')->toArray());
    }

    /**
     * @throws Exception
     */
    public function testItCanViewStandings(): void
    {
        self::loadFixtures(
            'motorsport.standing.teamStanding.redBullRacingAfterAustralianGP2022',
            'motorsport.standing.teamStanding.mercedesAfterAustralianGP2022',
            'motorsport.standing.teamStanding.redBullRacingAfterEmiliaRomagnaGP2022',
            'motorsport.standing.teamStanding.mercedesAfterEmiliaRomagnaGP2022',
        );

        $repository = new TeamStandingsForSeasonRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view(self::fixtureId('motorsport.championship.season.formulaOne2022'))->toArray();

        $expected = [
            2 => [
                self::fixtureId('motorsport.team.team.redBullRacing') => 18,
                self::fixtureId('motorsport.team.team.mercedes')      => 12,
            ],
            3 => [
                self::fixtureId('motorsport.team.team.redBullRacing') => 76,
                self::fixtureId('motorsport.team.team.mercedes')      => 12,
            ],
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
