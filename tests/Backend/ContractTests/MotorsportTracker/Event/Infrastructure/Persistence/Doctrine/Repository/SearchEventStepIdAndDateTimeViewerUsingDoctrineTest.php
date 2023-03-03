<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\SearchEventStepIdAndDateTimeViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\SearchEventStepIdAndDateTimeViewerUsingDoctrine
 */
final class SearchEventStepIdAndDateTimeViewerUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\SearchEventStepIdAndDateTimeViewerUsingDoctrineTest::testItCanFindAnEventStepProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindAnEventStep(string $keyword, string $type): void
    {
        $this->loadFixture('motorsport.event.eventStep.dutchGrandPrix2022Race');

        $repository = new SearchEventStepIdAndDateTimeViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::assertSame(
            $this->fixtureId('motorsport.event.eventStep.dutchGrandPrix2022Race'),
            $repository->search($seasonId, $keyword, $type)?->eventStepId(),
        );
    }

    /**
     * @return array<string , array{keyword: string, type: string}>
     */
    public function testItCanFindAnEventStepProvider(): array
    {
        return [
            'string formatting' => ['keyword' => 'dUtCh', 'type' => 'AcE'],
            'partial keyword'   => ['keyword' => 'Dutch', 'type' => 'Race'],
            'partial type'      => ['keyword' => 'Dutch GP', 'type' => 'ac'],
            'full'              => ['keyword' => 'Dutch GP', 'type' => 'Race'],
            'by venue'          => ['keyword' => 'Zandvoort', 'type' => 'race'],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResultByEventLabel(): void
    {
        $this->loadFixtures(
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022Race',
            'motorsport.event.eventStep.dutchGrandPrix2022Race',
        );

        $repository = new SearchEventStepIdAndDateTimeViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::expectException(NonUniqueResultException::class);

        $repository->search($seasonId, 'a', 'race');
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResultByStepType(): void
    {
        $this->loadFixtures(
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022SprintQualifying',
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022Race',
        );

        $repository = new SearchEventStepIdAndDateTimeViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::expectException(NonUniqueResultException::class);

        $repository->search($seasonId, 'emilia romagna', '');
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.event.eventStep.dutchGrandPrix2022Race');

        $repository = new SearchEventStepIdAndDateTimeViewerUsingDoctrine(self::entityManager());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        self::assertNull($repository->search($seasonId, 'dutch', 'sprint')); // wrong type
        self::assertNull($repository->search($seasonId, 'melbourne', 'race')); // wrong venue
        self::assertNull($repository->search($seasonId, 'australian', 'race')); // wrong label
        self::assertNull($repository->search('wrong season id', 'dutch', 'race')); // wrong season
    }
}
