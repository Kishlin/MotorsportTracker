<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\ChampionshipGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship
 */
final class ChampionshipGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAChampionship(): void
    {
        $championship = Championship::instance(
            new ChampionshipId(self::uuid()),
            new ChampionshipName('Formula One'),
            new ChampionshipSlug('formula1'),
        );

        $repository = new ChampionshipGatewayUsingDoctrine(self::entityManager());

        $repository->save($championship);

        self::assertAggregateRootWasSaved($championship);
    }
}
