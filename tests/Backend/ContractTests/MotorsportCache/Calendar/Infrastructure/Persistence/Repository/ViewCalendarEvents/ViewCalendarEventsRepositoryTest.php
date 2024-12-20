<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewCalendarEvents;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewCalendarEvents\ViewCalendarEventsRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewCalendarEvents\ViewCalendarEventsRepository
 */
final class ViewCalendarEventsRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItReturnsAnEmptyCalendarWhenThereIsNoData(): void
    {
        $repository = new ViewCalendarEventsRepository(self::connection());

        $jsonableCalendarView = $repository->view(new DateTimeImmutable(), new DateTimeImmutable());

        self::assertEmpty($jsonableCalendarView->toArray());
    }

    public function testItCanViewACalendarOfOneEvent(): void
    {
        self::loadFixture('calendar_event.formula_one_2022_dutch_gp');

        $repository = new ViewCalendarEventsRepository(self::connection());

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d', '2022-12-31');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $view = $repository->view($startDate, $endDate)->toArray();

        self::assertArrayHasKey('2022-11-22', $view);

        self::assertCount(1, $view['2022-11-22']);
        self::assertSame('formula-one_0_dutch-gp', $view['2022-11-22'][0]['slug']);
    }

    public function testItCanViewAComplexCalendar(): void
    {
        self::loadFixtures(
            'calendar_event.formula_one_2022_dutch_gp',
            'calendar_event.formula_one_2022_emilia_romagna_gp',
        );

        $repository = new ViewCalendarEventsRepository(self::connection());

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d', '2022-12-31');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $view = $repository->view($startDate, $endDate)->toArray();

        self::assertArrayHasKey('2022-11-22', $view);
        self::assertArrayHasKey('2022-04-23', $view);

        self::assertCount(1, $view['2022-11-22']);
        self::assertSame('formula-one_0_dutch-gp', $view['2022-11-22'][0]['slug']);

        self::assertCount(1, $view['2022-04-23']);
        self::assertSame('Emilia Romagna-gp', $view['2022-04-23'][0]['slug']);
    }

    public function testItCanFilterOnSpecificDate(): void
    {
        self::loadFixtures(
            'calendar_event.formula_one_2022_dutch_gp',
            'calendar_event.formula_one_2022_emilia_romagna_gp',
        );

        $repository = new ViewCalendarEventsRepository(self::connection());

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-11-01');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d', '2022-11-30');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $view = $repository->view($startDate, $endDate)->toArray();

        self::assertArrayNotHasKey('2022-04-23', $view);
        self::assertArrayHasKey('2022-11-22', $view);
        self::assertCount(1, $view);
    }
}
