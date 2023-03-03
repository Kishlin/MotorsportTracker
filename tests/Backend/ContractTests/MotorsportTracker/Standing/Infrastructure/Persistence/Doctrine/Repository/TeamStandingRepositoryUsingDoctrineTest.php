<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\TeamStandingRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\TeamStandingRepositoryUsingDoctrine
 */
final class TeamStandingRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnEntity(): void
    {
        self::loadFixtures(
            'motorsport.event.event.dutchGrandPrix2022',
            'motorsport.team.team.redBullRacing',
        );

        $teamStanding = TeamStanding::instance(
            new TeamStandingId(self::uuid()),
            new TeamStandingEventId(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new TeamStandingTeamId(self::fixtureId('motorsport.team.team.redBullRacing')),
            new TeamStandingPoints(5.0),
        );

        $repository = new TeamStandingRepositoryUsingDoctrine(self::entityManager());

        $repository->save($teamStanding);

        self::assertAggregateRootWasSaved($teamStanding);
    }

    public function testItDoesNotFindAMissingStanding(): void
    {
        $repository = new TeamStandingRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->find(
            new TeamStandingTeamId('dad4de6a-dfd8-44a6-93c7-3f10e1d2455c'),
            new TeamStandingEventId('6f8e65d6-0bda-4e48-8804-8dcc125e1c28'),
        ));
    }

    public function testItFindsAnExistingStanding(): void
    {
        self::loadFixture('motorsport.standing.teamStanding.redBullRacingAfterAustralianGP2022');

        $repository = new TeamStandingRepositoryUsingDoctrine(self::entityManager());

        $standing = $repository->find(
            new TeamStandingTeamId(self::fixtureId('motorsport.team.team.redBullRacing')),
            new TeamStandingEventId(self::fixtureId('motorsport.event.event.australianGrandPrix2022')),
        );

        self::assertInstanceOf(TeamStanding::class, $standing);
        self::assertSame(18.0, $standing->points()->value());
    }
}
