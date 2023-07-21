<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToChampionshipConverter;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToChampionshipPresentationConverter;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToSeasonConverter;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Fixtures\FixtureToDriverConverter;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures\FixtureToEventConverter;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures\FixtureToEventSessionConverter;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures\FixtureToSessionTypeConverter;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures\FixtureToClassificationConverter;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures\FixtureToEntryConverter;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures\FixtureToRaceLapConverter;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures\FixtureToRetirementConverter;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Fixture\FixtureToAnalyticsConverter;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Fixtures\FixtureToTeamConverter;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Fixtures\FixtureToVenueConverter;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

final class MotorsportTrackerFixtureConverterConfigurator
{
    private function __construct()
    {
    }

    public static function populateFixtureSaverWithConverters(FixtureSaver $fixtureSaver): void
    {
        $fixtureSaver->addConverter('motorsport.championship.championshipPresentation', new FixtureToChampionshipPresentationConverter());
        $fixtureSaver->addConverter('motorsport.championship.championship', new FixtureToChampionshipConverter());
        $fixtureSaver->addConverter('motorsport.championship.season', new FixtureToSeasonConverter());

        $fixtureSaver->addConverter('motorsport.driver.driver', new FixtureToDriverConverter());

        $fixtureSaver->addConverter('motorsport.event.event', new FixtureToEventConverter());
        $fixtureSaver->addConverter('motorsport.event.eventSession', new FixtureToEventSessionConverter());
        $fixtureSaver->addConverter('motorsport.event.sessionType', new FixtureToSessionTypeConverter());

        $fixtureSaver->addConverter('motorsport.result.entry', new FixtureToEntryConverter());
        $fixtureSaver->addConverter('motorsport.result.raceLap', new FixtureToRaceLapConverter());
        $fixtureSaver->addConverter('motorsport.result.retirement', new FixtureToRetirementConverter());
        $fixtureSaver->addConverter('motorsport.result.classification', new FixtureToClassificationConverter());

        $fixtureSaver->addConverter('motorsport.standing.analytics', new FixtureToAnalyticsConverter());

        $fixtureSaver->addConverter('motorsport.team.team', new FixtureToTeamConverter());

        $fixtureSaver->addConverter('motorsport.venue.venue', new FixtureToVenueConverter());
    }
}
