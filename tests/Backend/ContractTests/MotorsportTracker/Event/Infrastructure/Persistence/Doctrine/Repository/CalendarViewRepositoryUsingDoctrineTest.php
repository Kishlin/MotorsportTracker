<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CalendarViewRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CalendarViewRepositoryUsingDoctrine
 */
final class CalendarViewRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyCalendarWhenThereIsNoData(): void
    {
        $repository = new CalendarViewRepositoryUsingDoctrine(self::entityManager());

        self::assertEmpty($repository->view()->toArray());
    }

    /**
     * @throws Exception
     */
    public function testItCanViewACalendarOfOneEvent(): void
    {
        self::loadFixture('motorsport.event.eventStep.dutchGrandPrix2022Race');

        $repository = new CalendarViewRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view()->toArray();

        $expected = [
            '2022-09-04' => [
                '2022-09-04 13:00:00' => [
                    'championship' => self::fixtureId('motorsport.championship.championship.formulaOne'),
                    'venue'        => self::fixtureId('motorsport.venue.venue.zandvoort'),
                    'type'         => self::fixtureId('motorsport.event.stepType.race'),
                    'event'        => self::fixtureId('motorsport.event.event.dutchGrandPrix2022'),
                    'date_time'    => '2022-09-04 13:00:00',
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
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022SprintQualifying',
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022Race',
            'motorsport.event.eventStep.australianGrandPrix2022Race',
            'motorsport.event.eventStep.americasMotoGP2022Race',
        );

        $repository = new CalendarViewRepositoryUsingDoctrine(self::entityManager());

        $actual = $repository->view()->toArray();

        $expected = [
            '2022-04-10' => [
                '2022-04-10 05:00:00' => [
                    'championship' => self::fixtureId('motorsport.championship.championship.formulaOne'),
                    'venue'        => self::fixtureId('motorsport.venue.venue.melbourne'),
                    'type'         => self::fixtureId('motorsport.event.stepType.race'),
                    'event'        => self::fixtureId('motorsport.event.event.australianGrandPrix2022'),
                    'date_time'    => '2022-04-10 05:00:00',
                ],
                '2022-04-10 18:00:00' => [
                    'championship' => self::fixtureId('motorsport.championship.championship.motoGp'),
                    'venue'        => self::fixtureId('motorsport.venue.venue.circuitOfTheAmericas'),
                    'type'         => self::fixtureId('motorsport.event.stepType.race'),
                    'event'        => self::fixtureId('motorsport.event.event.americasMotoGP2022'),
                    'date_time'    => '2022-04-10 18:00:00',
                ],
            ],
            '2022-04-23' => [
                '2022-04-23 14:30:00' => [
                    'championship' => self::fixtureId('motorsport.championship.championship.formulaOne'),
                    'venue'        => self::fixtureId('motorsport.venue.venue.emiliaRomagna'),
                    'type'         => self::fixtureId('motorsport.event.stepType.sprintQualifying'),
                    'event'        => self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022'),
                    'date_time'    => '2022-04-23 14:30:00',
                ],
            ],
            '2022-04-24' => [
                '2022-04-24 13:00:00' => [
                    'championship' => self::fixtureId('motorsport.championship.championship.formulaOne'),
                    'venue'        => self::fixtureId('motorsport.venue.venue.emiliaRomagna'),
                    'type'         => self::fixtureId('motorsport.event.stepType.race'),
                    'event'        => self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022'),
                    'date_time'    => '2022-04-24 13:00:00',
                ],
            ],
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}