<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\TeamStandingRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\TeamStandingRepositoryUsingDoctrine
 */
final class TeamStandingRepositoryUsingDoctrineTest extends RepositoryContractTestCase
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
}
