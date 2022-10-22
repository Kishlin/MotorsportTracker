<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SeasonGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SeasonGatewayUsingDoctrine
 */
final class SeasonGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveASeason(): void
    {
        self::loadFixture('motorsport.championship.championship.formulaOne');

        $season = Season::instance(
            new SeasonId(self::uuid()),
            new SeasonYear(2022),
            new SeasonChampionshipId(self::fixtureId('motorsport.championship.championship.formulaOne'))
        );

        $repository = new SeasonGatewayUsingDoctrine(self::entityManager());

        $repository->save($season);

        self::assertAggregateRootWasSaved($season);
    }
}
