<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures\FixtureToChampionshipPresentationConverter;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

final class MotorsportTrackerFixtureConverterConfigurator
{
    private function __construct()
    {
    }

    public static function populateFixtureSaverWithConverters(FixtureSaver $fixtureSaver): void
    {
        $fixtureSaver->addConverter('motorsport.championship.championshipPresentation', new FixtureToChampionshipPresentationConverter());
    }
}
