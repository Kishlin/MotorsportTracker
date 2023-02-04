<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewColor;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewId;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewName;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewType;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewVenueLabel;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\CalendarEventStepViewRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\CalendarEventStepViewRepositoryUsingDoctrine
 */
final class CalendarEventStepViewRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveACalendarView(): void
    {
        $calendarEventStepView = CalendarEventStepView::instance(
            new CalendarEventStepViewId('14cb186f-b63f-494f-bafd-85199594c8af'),
            new CalendarEventStepViewChampionshipSlug('slug'),
            new CalendarEventStepViewColor('color'),
            new CalendarEventStepViewIcon('icon'),
            new CalendarEventStepViewName('name'),
            new CalendarEventStepViewVenueLabel('venue'),
            new CalendarEventStepViewType('type'),
            new CalendarEventStepViewDateTime(new \DateTimeImmutable()),
        );

        $repository = new CalendarEventStepViewRepositoryUsingDoctrine(self::entityManager());

        $repository->save($calendarEventStepView);

        self::assertAggregateRootWasSaved($calendarEventStepView);
    }
}
