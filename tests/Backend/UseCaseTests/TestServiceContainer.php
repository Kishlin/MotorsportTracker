<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\Services\Country\CountryServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Calendar\CalendarServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship\ChampionshipServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Driver\DriverServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\EventServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\EventSessionServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\StepTypeServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team\TeamPresentationServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team\TeamServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue\VenueServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Messaging\MessagingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Persistence\Fixtures\FixturesServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Randomness\RandomnessServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Time\TimeServicesTrait;

final class TestServiceContainer
{
    use CalendarServicesTrait;
    use ChampionshipServicesTrait;
    use CountryServicesTrait;
    use DriverServicesTrait;
    use EventServicesTrait;
    use EventSessionServicesTrait;
    use FixturesServicesTrait;
    use MessagingServicesTrait;
    use RandomnessServicesTrait;
    use StepTypeServicesTrait;
    use TeamPresentationServicesTrait;
    use TeamServicesTrait;
    use TimeServicesTrait;
    use VenueServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
