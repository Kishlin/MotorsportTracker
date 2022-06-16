<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\TeamRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Team\TeamProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\TeamRepositoryUsingDoctrine
 */
final class TeamGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveATeam(): void
    {
        self::loadFixtures(CountryProvider::austria());

        $team = TeamProvider::redBullRacing();

        $repository = new TeamRepositoryUsingDoctrine(self::entityManager());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }
}
