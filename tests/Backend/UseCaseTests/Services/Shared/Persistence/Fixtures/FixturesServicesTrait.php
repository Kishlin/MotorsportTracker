<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Persistence\Fixtures;

use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\Fixtures\FixturesSaverForUseCaseTests;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

trait FixturesServicesTrait
{
    private ?FixtureLoader $fixtureLoader = null;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function serviceContainer(): TestServiceContainer;

    public function fixtureLoader(): FixtureLoader
    {
        if (null === $this->fixtureLoader) {
            $fixtureSaver = new FixturesSaverForUseCaseTests($this->serviceContainer());

            CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
            MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);

            $this->fixtureLoader = new FixtureLoader(
                $this->uuidGenerator(),
                $fixtureSaver,
                '/app/etc/Fixtures',
            );
        }

        return $this->fixtureLoader;
    }
}
