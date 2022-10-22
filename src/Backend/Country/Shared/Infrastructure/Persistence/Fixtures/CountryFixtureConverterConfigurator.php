<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Country\Infrastructure\Persistence\Fixtures\FixtureToCountryConverter;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

final class CountryFixtureConverterConfigurator
{
    private function __construct()
    {
    }

    public static function populateFixtureSaverWithConverters(FixtureSaver $fixtureSaver): void
    {
        $fixtureSaver->addConverter('country.country', new FixtureToCountryConverter());
    }
}
