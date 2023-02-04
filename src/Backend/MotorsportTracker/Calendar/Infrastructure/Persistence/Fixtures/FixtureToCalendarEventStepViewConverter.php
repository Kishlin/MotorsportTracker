<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewColor;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewId;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewName;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewType;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewVenueLabel;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToCalendarEventStepViewConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return CalendarEventStepView::instance(
            new CalendarEventStepViewId($fixture->identifier()),
            new CalendarEventStepViewChampionshipSlug($fixture->getString('championshipSlug')),
            new CalendarEventStepViewColor($fixture->getString('color')),
            new CalendarEventStepViewIcon($fixture->getString('icon')),
            new CalendarEventStepViewName($fixture->getString('name')),
            new CalendarEventStepViewVenueLabel($fixture->getString('venueLabel')),
            new CalendarEventStepViewType($fixture->getString('type')),
            new CalendarEventStepViewDateTime($fixture->getDateTime('dateTime')),
        );
    }
}
