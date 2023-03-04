<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Persistence\Fixtures;

use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportCache\Shared\Infrastructure\Persistence\Fixtures\MotorsportCacheFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\Fixtures\FixturesSaverForUseCaseTests;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

trait FixturesServicesTrait
{
    private ?FixtureLoader $coreFixtureLoader  = null;
    private ?FixtureLoader $cacheFixtureLoader = null;

    private ?FixtureSaver $fixtureSaver = null;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function serviceContainer(): TestServiceContainer;

    public function coreFixtureLoader(): FixtureLoader
    {
        if (null === $this->coreFixtureLoader) {
            $this->coreFixtureLoader = new FixtureLoader(
                $this->uuidGenerator(),
                $this->fixtureSaver(),
                '/app/etc/Fixtures/Core',
            );
        }

        return $this->coreFixtureLoader;
    }

    public function cacheFixtureLoader(): FixtureLoader
    {
        if (null === $this->cacheFixtureLoader) {
            $this->cacheFixtureLoader = new FixtureLoader(
                $this->uuidGenerator(),
                $this->fixtureSaver(),
                '/app/etc/Fixtures/Cache',
            );
        }

        return $this->cacheFixtureLoader;
    }

    public function fixtureSaver(): FixtureSaver
    {
        if (null === $this->fixtureSaver) {
            $this->fixtureSaver = new FixturesSaverForUseCaseTests($this->serviceContainer());

            CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters($this->fixtureSaver);
            MotorsportCacheFixtureConverterConfigurator::populateFixtureSaverWithConverters($this->fixtureSaver);
            MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters($this->fixtureSaver);
        }

        return $this->fixtureSaver;
    }
}
