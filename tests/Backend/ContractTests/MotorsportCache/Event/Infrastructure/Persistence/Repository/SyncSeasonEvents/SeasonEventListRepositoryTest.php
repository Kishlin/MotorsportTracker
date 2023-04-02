<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Event\Infrastructure\Persistence\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncSeasonEvents\SeasonEventListRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncSeasonEvents\SeasonEventListRepository
 */
final class SeasonEventListRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindEvents(): void
    {
        self::loadFixtures(
            'motorsport.event.event.emiliaRomagnaGrandPrix2022',
            'motorsport.event.event.australianGrandPrix2022',
            'motorsport.event.event.dutchGrandPrix2022',
        );

        $repository = new SeasonEventListRepository(self::connection());

        $events = $repository->findEventsForSeason(
            new StringValueObject('Formula One'),
            new StrictlyPositiveIntValueObject(2022),
        );

        self::assertCount(3, $events->data());

        self::assertArrayHasKey('dutch-gp', $events->data());
        self::assertArrayHasKey('australian-gp', $events->data());
        self::assertArrayHasKey('emilia-romagna-gp', $events->data());
    }

    public function testItFiltersOutEvents(): void
    {
        self::loadFixtures(
            'motorsport.event.event.americasMotoGP2022', // Wrong Championship: MotoGP
            'motorsport.event.event.hungarianGrandPrix2019', // Wrong Year: 2019
            'motorsport.event.event.dutchGrandPrix2022',
        );

        $repository = new SeasonEventListRepository(self::connection());

        $events = $repository->findEventsForSeason(
            new StringValueObject('Formula One'),
            new StrictlyPositiveIntValueObject(2022),
        );

        self::assertCount(1, $events->data());

        self::assertArrayHasKey('dutch-gp', $events->data());
    }

    public function testItReturnsAnEmptyArrayWhenThereAreNone(): void
    {
        $repository = new SeasonEventListRepository(self::connection());

        $events = $repository->findEventsForSeason(
            new StringValueObject('Formula One'),
            new StrictlyPositiveIntValueObject(2022),
        );

        self::assertEmpty($events->data());
    }
}
