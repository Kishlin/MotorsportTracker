<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\SearchCarViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\SearchCarViewerUsingDoctrine
 */
final class SearchCarViewerUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\SearchCarViewerUsingDoctrineTest::testItCanFindACarProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindACar(int $number, string $team, string $championship, int $year): void
    {
        $this->loadFixture('motorsport.car.car.redBullRacing2022FirstCar');

        $repository = new SearchCarViewerUsingDoctrine(self::entityManager());

        self::assertSame(
            $this->fixtureId('motorsport.car.car.redBullRacing2022FirstCar'),
            $repository->search($number, $team, $championship, $year)?->value(),
        );
    }

    /**
     * @return array<string , array{number: int, team: string, championship: string, year: int}>
     */
    public function testItCanFindACarProvider(): array
    {
        return [
            'complete' => [
                'number'       => 1,
                'team'         => 'Red Bull Racing',
                'championship' => 'Formula One',
                'year'         => 2022,
            ],
            'partial' => [
                'number'       => 1,
                'team'         => 'Red Bull',
                'championship' => 'Formula1',
                'year'         => 2022,
            ],
            'string formatting' => [
                'number'       => 1,
                'team'         => 'bul',
                'championship' => 'one',
                'year'         => 2022,
            ],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResult(): void
    {
        $this->loadFixtures(
            'motorsport.car.car.redBullRacing2019SecondCar',
            'motorsport.car.car.toroRosso2019FirstCar',
        );

        $repository = new SearchCarViewerUsingDoctrine(self::entityManager());

        self::expectException(NonUniqueResultException::class);

        $repository->search(10, 'r', 'formula1', 2019);
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.car.car.redBullRacing2022FirstCar');

        $repository = new SearchCarViewerUsingDoctrine(self::entityManager());

        self::assertNull($repository->search(11, 'red bull', 'formula one', 2022)); // not exist
        self::assertNull($repository->search(11, 'red bull', 'formula one', 2021)); // wrong year
        self::assertNull($repository->search(11, 'mercedes', 'formula one', 2022)); // wrong team
        self::assertNull($repository->search(44, 'red bull', 'formula one', 2022)); // wrong number
        self::assertNull($repository->search(11, 'red bull', 'formula two', 2022)); // wrong championship
    }
}
