<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SeasonGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SeasonGatewayUsingDoctrine
 */
final class SeasonGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = ChampionshipProvider::championship();
        $season       = SeasonProvider::forChampionship($championship->id());

        self::loadFixtures($championship);

        $repository = new SeasonGatewayUsingDoctrine(self::entityManager());

        $repository->save($season);

        self::assertAggregateRootWasSaved($season);
    }
}
