<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\SearchEventViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\SearchEventViewerUsingDoctrine
 */
final class SearchEventViewerUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\SearchEventViewerUsingDoctrineTest::testItCanFindAnEventProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindAnEvent(string $keyword): void
    {
        $this->loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new SearchEventViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::assertSame(
            $this->fixtureId('motorsport.event.event.dutchGrandPrix2022'),
            $repository->search($seasonId, $keyword)?->value(),
        );
    }

    /**
     * @return array<string , array{keyword: string}>
     */
    public function testItCanFindAnEventProvider(): array
    {
        return [
            'string formatting' => ['keyword' => 'dUtCh'],
            'partial keyword'   => ['keyword' => 'Dutch'],
            'full'              => ['keyword' => 'Dutch GP'],
            'by venue'          => ['keyword' => 'Zandvoort'],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResultByEventLabel(): void
    {
        $this->loadFixtures(
            'motorsport.event.event.emiliaRomagnaGrandPrix2022',
            'motorsport.event.event.dutchGrandPrix2022',
        );

        $repository = new SearchEventViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::expectException(NonUniqueResultException::class);

        $repository->search($seasonId, 'a');
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new SearchEventViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::assertNull($repository->search($seasonId, 'melbourne')); // wrong venue
        self::assertNull($repository->search($seasonId, 'australian')); // wrong label
        self::assertNull($repository->search('wrong season id', 'dutch')); // wrong season
    }
}
