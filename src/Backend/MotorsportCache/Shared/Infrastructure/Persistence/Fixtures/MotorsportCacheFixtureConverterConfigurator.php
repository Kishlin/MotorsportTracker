<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Fixtures\FixtureToCalendarEventConverter;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Fixtures\FixtureToCalendarEventStepViewConverter;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

final class MotorsportCacheFixtureConverterConfigurator
{
    private function __construct()
    {
    }

    public static function populateFixtureSaverWithConverters(FixtureSaver $fixtureSaver): void
    {
        $fixtureSaver->addConverter('motorsport.calendar.calendarEventStepView', new FixtureToCalendarEventStepViewConverter());
        $fixtureSaver->addConverter('motorsport.calendar.calendarEvent', new FixtureToCalendarEventConverter());
    }
}
