<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Fixtures\FixtureToCalendarEventConverter;
use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Fixtures\FixtureToEventCachedConverter;
use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Fixtures\FixtureToSeasonEventsConverter;
use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Fixtures\FixtureToEventResultsByRace;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

final class MotorsportCacheFixtureConverterConfigurator
{
    private function __construct()
    {
    }

    public static function populateFixtureSaverWithConverters(FixtureSaver $fixtureSaver): void
    {
        $fixtureSaver->addConverter('motorsport.calendar.calendarEvent', new FixtureToCalendarEventConverter());

        $fixtureSaver->addConverter('motorsport.event.seasonEvents', new FixtureToSeasonEventsConverter());
        $fixtureSaver->addConverter('motorsport.event.events', new FixtureToEventCachedConverter());

        $fixtureSaver->addConverter('motorsport.result.eventResultsByRace', new FixtureToEventResultsByRace());
    }
}
