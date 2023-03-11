<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\ViewCalendarEvents;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\ViewCalendarEvents\ViewCalendarEventsRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\ViewCalendarEvents\ViewCalendarEventsRepositoryUsingDoctrine
 */
final class ViewCalendarEventsRepositoryUsingDoctrineTest extends CacheLegacyRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyCalendarWhenThereIsNoData(): void
    {
        $repository = new ViewCalendarEventsRepositoryUsingDoctrine(self::entityManager());

        $jsonableCalendarView = $repository->view(new DateTimeImmutable(), new DateTimeImmutable());

        self::assertEmpty($jsonableCalendarView->toArray());
    }

    /**
     * @throws Exception
     */
    public function testItCanViewACalendarOfOneEvent(): void
    {
        self::loadFixture('motorsport.calendar.calendarEvent.formulaOne2022DutchGP');

        $repository = new ViewCalendarEventsRepositoryUsingDoctrine(self::entityManager());

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d', '2022-12-31');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $view = $repository->view($startDate, $endDate)->toArray();

        self::assertArrayHasKey('2022-11-22', $view);

        self::assertCount(1, $view['2022-11-22']);
        self::assertSame('formula-one_0_dutch-gp', $view['2022-11-22'][0]['slug']);
    }

    /**
     * @throws Exception
     */
    public function testItCanViewAComplexCalendar(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEvent.formulaOne2022DutchGP',
            'motorsport.calendar.calendarEvent.formulaOne2022EmiliaRomagnaGP',
        );

        $repository = new ViewCalendarEventsRepositoryUsingDoctrine(self::entityManager());

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

    /**
     * @throws Exception
     */
    public function testItCanFilterOnSpecificDate(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEvent.formulaOne2022DutchGP',
            'motorsport.calendar.calendarEvent.formulaOne2022EmiliaRomagnaGP',
        );

        $repository = new ViewCalendarEventsRepositoryUsingDoctrine(self::entityManager());

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
