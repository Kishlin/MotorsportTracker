<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonSchedule;

use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonSchedule\ViewSeasonScheduleRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonSchedule\ViewSeasonScheduleRepository
 */
final class ViewSeasonScheduleRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItReturnsAnEmptyScheduleWhenThereIsNoData(): void
    {
        $repository = new ViewSeasonScheduleRepository(self::connection());

        $jsonableCalendarView = $repository->viewSchedule('MotoGP', 1949);

        self::assertEmpty($jsonableCalendarView->toArray());
    }

    public function testItCanViewAScheduleOfOneEvent(): void
    {
        self::loadFixture('motorsport.calendar.calendarEvent.motoGP1949TouristTrophy');

        $repository = new ViewSeasonScheduleRepository(self::connection());

        $view = $repository->viewSchedule('MotoGP', 1949)->toArray();

        self::assertArrayHasKey('1949-06-16', $view);

        self::assertCount(1, $view['1949-06-16']);
        self::assertSame('motogp_1949_tourist-trophy', $view['1949-06-16'][0]['slug']);
    }

    public function testItCanViewAComplexCalendar(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEvent.motoGP1949TouristTrophy',
            'motorsport.calendar.calendarEvent.motoGP1949SwitzerlandGrandPrix',
        );

        $repository = new ViewSeasonScheduleRepository(self::connection());

        $view = $repository->viewSchedule('MotoGP', 1949)->toArray();

        self::assertArrayHasKey('1949-06-16', $view);
        self::assertArrayHasKey('1949-07-03', $view);

        self::assertCount(1, $view['1949-06-16']);
        self::assertSame('motogp_1949_tourist-trophy', $view['1949-06-16'][0]['slug']);

        self::assertCount(1, $view['1949-07-03']);
        self::assertSame('motogp_1949_switzerland-grand-prix', $view['1949-07-03'][0]['slug']);
    }

    public function testItCanFilterOnSpecificDate(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEvent.motoGP1949TouristTrophy',
            'motorsport.calendar.calendarEvent.motoGP1949SwitzerlandGrandPrix',
            'motorsport.calendar.calendarEvent.formulaOne1950SwissGrandPrix',
        );

        $repository = new ViewSeasonScheduleRepository(self::connection());

        $view = $repository->viewSchedule('Formula One', 1950)->toArray();

        self::assertArrayNotHasKey('1949-07-03', $view);
        self::assertArrayHasKey('1950-06-03', $view);
        self::assertCount(1, $view);
    }
}
