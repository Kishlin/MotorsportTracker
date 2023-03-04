<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\ViewCalendarRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\ViewCalendarRepositoryUsingDoctrine
 */
final class ViewCalendarRepositoryUsingDoctrineTest extends CacheRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyCalendarWhenThereIsNoData(): void
    {
        $repository = new ViewCalendarRepositoryUsingDoctrine(self::entityManager());

        $jsonableCalendarView = $repository->view(new DateTimeImmutable(), new DateTimeImmutable());

        self::assertEmpty($jsonableCalendarView->toArray());
    }

    /**
     * @throws Exception
     */
    public function testItCanViewACalendarOfOneEvent(): void
    {
        self::loadFixture('motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite');

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-09-01');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d', '2022-09-30');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $repository = new ViewCalendarRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view($startDate, $endDate)->toArray();

        $expected = [
            '2022-09-04' => [
                '2022-09-04 13:00:00' => [
                    'championship_slug' => 'formula1',
                    'color'             => '#fff',
                    'icon'              => 'f1.png',
                    'name'              => 'Dutch GP',
                    'venue_label'       => 'Circuit Zandvoort',
                    'type'              => 'race',
                    'date_time'         => '2022-09-04 13:00:00',
                    'reference'         => 'a7a9bcb9-2fb1-41d4-bc25-223c83a2564a',
                ],
            ],
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testItCanViewAComplexCalendar(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEventStepView.emiliaRomagnaGrandPrix2022SprintQualifyingWhite',
            'motorsport.calendar.calendarEventStepView.emiliaRomagnaGrandPrix2022RaceWhite',
            'motorsport.calendar.calendarEventStepView.australianGrandPrix2022RaceWhite',
            'motorsport.calendar.calendarEventStepView.americasMotoGP2022RaceBlack',
        );

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-04-01');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d', '2022-04-30');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $repository = new ViewCalendarRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view($startDate, $endDate)->toArray();

        $expected = [
            '2022-04-10' => [
                '2022-04-10 05:00:00' => [
                    'championship_slug' => 'formula1',
                    'color'             => '#fff',
                    'icon'              => 'f1.png',
                    'name'              => 'Australian GP',
                    'venue_label'       => 'Melbourne Grand Prix Circuit',
                    'type'              => 'race',
                    'date_time'         => '2022-04-10 05:00:00',
                    'reference'         => 'de5737f9-3005-42ae-b4af-96c895ac3d8c',
                ],
                '2022-04-10 18:00:00' => [
                    'championship_slug' => 'motogp',
                    'color'             => '#ddd',
                    'icon'              => 'motogp.png',
                    'name'              => 'Americas GP',
                    'venue_label'       => 'Circuit of the Americas',
                    'type'              => 'race',
                    'date_time'         => '2022-04-10 18:00:00',
                    'reference'         => 'ebb73545-bbbb-4538-86b9-03aeefdfb298',
                ],
            ],
            '2022-04-23' => [
                '2022-04-23 14:30:00' => [
                    'championship_slug' => 'formula1',
                    'color'             => '#fff',
                    'icon'              => 'f1.png',
                    'name'              => 'Emilia Romagna GP',
                    'venue_label'       => 'Autodromo Internazionale Enzo e Dino Ferrari',
                    'type'              => 'sprint qualifying',
                    'date_time'         => '2022-04-23 14:30:00',
                    'reference'         => 'c573aac1-6224-4045-abad-2d4a498e0a1b',
                ],
            ],
            '2022-04-24' => [
                '2022-04-24 13:00:00' => [
                    'championship_slug' => 'formula1',
                    'color'             => '#fff',
                    'icon'              => 'f1.png',
                    'name'              => 'Emilia Romagna GP',
                    'venue_label'       => 'Autodromo Internazionale Enzo e Dino Ferrari',
                    'type'              => 'race',
                    'date_time'         => '2022-04-24 13:00:00',
                    'reference'         => '2c500ba2-f78b-4393-8bb3-e055b2d8005c',
                ],
            ],
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testItCanFilterOnSpecificDates(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEventStepView.australianGrandPrix2022RaceWhite',
            'motorsport.calendar.calendarEventStepView.emiliaRomagnaGrandPrix2022SprintQualifyingWhite',
            'motorsport.calendar.calendarEventStepView.emiliaRomagnaGrandPrix2022RaceWhite',
        );

        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', '2022-04-22');
        $endDate   = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2022-04-23 23:59:59');
        assert($startDate instanceof DateTimeImmutable);
        assert($endDate instanceof DateTimeImmutable);

        $repository = new ViewCalendarRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view($startDate, $endDate)->toArray();

        $expected = [
            '2022-04-23' => [
                '2022-04-23 14:30:00' => [
                    'championship_slug' => 'formula1',
                    'color'             => '#fff',
                    'icon'              => 'f1.png',
                    'name'              => 'Emilia Romagna GP',
                    'venue_label'       => 'Autodromo Internazionale Enzo e Dino Ferrari',
                    'type'              => 'sprint qualifying',
                    'date_time'         => '2022-04-23 14:30:00',
                    'reference'         => 'c573aac1-6224-4045-abad-2d4a498e0a1b',
                ],
            ],
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
