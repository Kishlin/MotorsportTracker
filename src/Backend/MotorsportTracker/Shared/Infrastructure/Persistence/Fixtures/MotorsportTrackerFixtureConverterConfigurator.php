<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Fixtures\FixtureToCalendarEventStepViewConverter;
use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Fixtures\FixtureToCarConverter;
use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Fixtures\FixtureToDriverMoveConverter;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToChampionshipConverter;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToChampionshipPresentationConverter;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToSeasonConverter;
use Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Fixtures\FixtureToDriverConverter;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures\FixtureToEventConverter;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures\FixtureToEventStepConverter;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures\FixtureToStepTypeConverter;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Fixtures\FixtureToRacerConverter;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures\FixtureToResultConverter;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Fixtures\FixtureToDriverStandingConverter;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Fixtures\FixtureToTeamStandingConverter;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Fixtures\FixtureToTeamConverter;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Fixtures\FixtureToTeamPresentationConverter;
use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Fixtures\FixtureToVenueConverter;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

final class MotorsportTrackerFixtureConverterConfigurator
{
    private function __construct()
    {
    }

    public static function populateFixtureSaverWithConverters(FixtureSaver $fixtureSaver): void
    {
        $fixtureSaver->addConverter('motorsport.calendar.calendarEventStepView', new FixtureToCalendarEventStepViewConverter());

        $fixtureSaver->addConverter('motorsport.car.car', new FixtureToCarConverter());
        $fixtureSaver->addConverter('motorsport.car.driverMove', new FixtureToDriverMoveConverter());

        $fixtureSaver->addConverter('motorsport.championship.championshipPresentation', new FixtureToChampionshipPresentationConverter());
        $fixtureSaver->addConverter('motorsport.championship.championship', new FixtureToChampionshipConverter());
        $fixtureSaver->addConverter('motorsport.championship.season', new FixtureToSeasonConverter());

        $fixtureSaver->addConverter('motorsport.driver.driver', new FixtureToDriverConverter());

        $fixtureSaver->addConverter('motorsport.event.event', new FixtureToEventConverter());
        $fixtureSaver->addConverter('motorsport.event.eventStep', new FixtureToEventStepConverter());
        $fixtureSaver->addConverter('motorsport.event.stepType', new FixtureToStepTypeConverter());

        $fixtureSaver->addConverter('motorsport.racer.racer', new FixtureToRacerConverter());

        $fixtureSaver->addConverter('motorsport.result.result', new FixtureToResultConverter());

        $fixtureSaver->addConverter('motorsport.standing.teamStanding', new FixtureToTeamStandingConverter());
        $fixtureSaver->addConverter('motorsport.standing.driverStanding', new FixtureToDriverStandingConverter());

        $fixtureSaver->addConverter('motorsport.team.team', new FixtureToTeamConverter());
        $fixtureSaver->addConverter('motorsport.team.teamPresentation', new FixtureToTeamPresentationConverter());

        $fixtureSaver->addConverter('motorsport.venue.venue', new FixtureToVenueConverter());
    }
}
