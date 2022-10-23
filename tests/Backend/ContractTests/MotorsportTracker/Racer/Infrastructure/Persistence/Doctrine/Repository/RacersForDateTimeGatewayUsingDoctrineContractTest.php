<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\SeasonId;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacersForDateTimeAndSeasonGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacersForDateTimeAndSeasonGatewayUsingDoctrine
 */
final class RacersForDateTimeGatewayUsingDoctrineContractTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanRetrieveARacerForADate(): void
    {
        self::loadFixtures(
            'motorsport.racer.racer.perezAtRedBullRacingIn2022',
            'motorsport.racer.racer.verstappenAtRedBullRacingIn2022',
        );

        $repository = new RacersForDateTimeAndSeasonGatewayUsingDoctrine(self::entityManager());

        $racers = $repository->findRacersForDateTimeAndSeason(
            new DateTimeImmutable('2022-06-01'),
            new SeasonId(self::fixtureId('motorsport.championship.season.formulaOne2022')),
        );

        self::assertCount(2, $racers);
        self::assertSame('Sergio Perez', $racers[0]->driverName());
        self::assertSame('Max Verstappen', $racers[1]->driverName());
    }
}
