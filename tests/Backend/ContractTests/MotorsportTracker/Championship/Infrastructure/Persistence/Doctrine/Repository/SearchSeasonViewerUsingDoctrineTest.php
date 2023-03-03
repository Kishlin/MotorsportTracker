<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchSeasonViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchSeasonViewerUsingDoctrine
 */
final class SearchSeasonViewerUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\SearchSeasonViewerUsingDoctrineTest::testItCanFindAChampionshipProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindAChampionship(string $championship, int $year): void
    {
        $this->loadFixture('motorsport.championship.season.formulaOne2022');

        $repository = new SearchSeasonViewerUsingDoctrine(self::entityManager());

        self::assertSame(
            $this->fixtureId('motorsport.championship.season.formulaOne2022'),
            $repository->search($championship, $year)?->value(),
        );
    }

    /**
     * @return array<string , array{championship: string, year: int}>
     */
    public function testItCanFindAChampionshipProvider(): array
    {
        return [
            'by slug'           => ['championship' => 'formula1', 'year' => 2022],
            'by full name'      => ['championship' => 'Formula One', 'year' => 2022],
            'by partial name'   => ['championship' => 'ormu', 'year' => 2022],
            'string formatting' => ['championship' => 'ormu', 'year' => 2022],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResult(): void
    {
        $this->loadFixtures(
            'motorsport.championship.season.formulaOne2022',
            'motorsport.championship.season.motoGP2022',
        );

        $repository = new SearchSeasonViewerUsingDoctrine(self::entityManager());

        self::expectException(NonUniqueResultException::class);

        $repository->search('o', 2022);
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.championship.season.formulaOne2022');

        $repository = new SearchSeasonViewerUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('wec', 2022)); // not exist
        self::assertNull($repository->search('formula1', 2023)); // wrong year
        self::assertNull($repository->search('motogp', 2022)); // wrong championship
    }
}
