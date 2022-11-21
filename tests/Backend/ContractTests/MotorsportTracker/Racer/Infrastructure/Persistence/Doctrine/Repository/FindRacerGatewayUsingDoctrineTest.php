<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\FindRacerGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\FindRacerGatewayUsingDoctrine
 */
final class FindRacerGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws DoctrineException|Exception|NonUniqueResultException
     */
    public function testItCanFindARacer(): void
    {
        self::loadFixture('motorsport.racer.racer.verstappenAtRedBullRacingIn2022');

        $driverId = $this->fixtureId('motorsport.driver.driver.maxVerstappen');

        $repository = new FindRacerGatewayUsingDoctrine(self::entityManager());

        $racer = $repository->find($driverId, 'Formula One', '2022-06-01 00:00:00');

        self::assertNotNull($racer);

        self::assertSame(
            self::fixtureId('motorsport.racer.racer.verstappenAtRedBullRacingIn2022'),
            $racer->id()->value(),
        );
    }

    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\FindRacerGatewayUsingDoctrineTest::itFailsToFindTheRacerDataProvider()
     *
     * @throws DoctrineException|Exception|NonUniqueResultException
     */
    public function testItFailsToFindTheRacer(string $driver, string $championship, string $dateTime): void
    {
        self::loadFixtures(
            'motorsport.racer.racer.verstappenAtRedBullRacingIn2022',
            'motorsport.driver.driver.lewisHamilton',
        );

        $repository = new FindRacerGatewayUsingDoctrine(self::entityManager());

        self::assertNull($repository->find($this->fixtureId($driver), $championship, $dateTime));
    }

    /**
     * @return array<array{driver: string, championship: string, dateTime: string}>
     */
    public function itFailsToFindTheRacerDataProvider(): array
    {
        return [
            'wrong driver' => [
                'driver'       => 'motorsport.driver.driver.lewisHamilton',
                'championship' => 'Formula One',
                'dateTime'     => '2022-06-01 00:00:00',
            ],
            'date before start date' => [
                'driver'       => 'motorsport.driver.driver.maxVerstappen',
                'championship' => 'Formula One',
                'dateTime'     => '1993-11-22 01:00:00',
            ],
            'date after end date' => [
                'driver'       => 'motorsport.driver.driver.maxVerstappen',
                'championship' => 'Formula One',
                'dateTime'     => '2055-01-01 00:00:00',
            ],
            'wrong championship' => [
                'driver'       => 'motorsport.driver.driver.maxVerstappen',
                'championship' => 'WEC',
                'dateTime'     => '2022-06-01 00:00:00',
            ],
        ];
    }
}
