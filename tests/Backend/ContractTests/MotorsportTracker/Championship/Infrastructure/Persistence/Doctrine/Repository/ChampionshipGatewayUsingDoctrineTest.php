<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\ChampionshipGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship
 */
final class ChampionshipGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = ChampionshipProvider::championship();

        $repository = new ChampionshipGatewayUsingDoctrine(self::entityManager());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }
}
