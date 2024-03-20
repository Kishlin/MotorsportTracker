<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindSeriesRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindSeriesRepository
 */
final class FindSeriesRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindData(): void
    {
        self::loadFixtures(
            'championship_presentation.formula_one_white',
            'season.formula_one_2022',
        );

        $repository = new FindSeriesRepository(self::connection());

        $series = $repository->findForChampionship(new StringValueObject('Formula One'), new PositiveIntValueObject(2022));

        self::assertNotNull($series);

        self::assertSame('Formula One', $series->data()['name']);
        self::assertSame('formula-one', $series->data()['slug']);
        self::assertSame(2022, $series->data()['year']);
        self::assertSame('#fff', $series->data()['color']);
        self::assertSame('f1.png', $series->data()['icon']);
    }

    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new FindSeriesRepository(self::connection());

        self::assertNull($repository->findForChampionship(new StringValueObject('Not Exist'), new PositiveIntValueObject(2022)));
    }
}
