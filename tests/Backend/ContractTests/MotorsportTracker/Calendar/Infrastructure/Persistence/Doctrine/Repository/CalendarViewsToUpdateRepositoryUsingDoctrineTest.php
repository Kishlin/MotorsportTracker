<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\CalendarViewsToUpdateRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\CalendarViewsToUpdateRepositoryUsingDoctrine
 */
final class CalendarViewsToUpdateRepositoryUsingDoctrineTest extends CacheRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanFindAllViews(): void
    {
        $australianGPFixture = 'motorsport.calendar.calendarEventStepView.australianGrandPrix2022RaceWhite';
        $dutchGPFixture      = 'motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite';

        self::loadFixtures($australianGPFixture, $dutchGPFixture);

        $repository = new CalendarViewsToUpdateRepositoryUsingDoctrine(self::entityManager());

        self::assertEqualsCanonicalizing(
            [self::fixtureId($australianGPFixture), self::fixtureId($dutchGPFixture)],
            $repository->findForSlug('formula1')->idList(),
        );
    }

    /**
     * @throws Exception
     */
    public function testItReturnsAnEmptyArrayWhenNoViewMatch(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEventStepView.dutchGrandPrix2022RaceWhite',
        );

        $repository = new CalendarViewsToUpdateRepositoryUsingDoctrine(self::entityManager());

        self::assertEmpty($repository->findForSlug('motogp')->idList());
        self::assertEmpty($repository->findForSlug('yada yada yada')->idList());
    }
}
