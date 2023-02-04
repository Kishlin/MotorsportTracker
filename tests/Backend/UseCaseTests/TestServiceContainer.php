<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\Services\Country\CountryServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Calendar\CalendarServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Car\CarServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Car\DriverMoveServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship\ChampionshipServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Driver\DriverServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\CalendarServicesTrait as LegacyCalendarServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\EventServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\EventStepServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\SearchEventServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\SearchEventStepServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event\StepTypeServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Racer\RacerServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Result\ResultServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Standing\StandingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team\TeamServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue\VenueServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Messaging\MessagingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Persistence\Fixtures\FixturesServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Randomness\RandomnessServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Time\TimeServicesTrait;

final class TestServiceContainer
{
    use CalendarServicesTrait;
    use CarServicesTrait;
    use ChampionshipServicesTrait;
    use CountryServicesTrait;
    use DriverMoveServicesTrait;
    use DriverServicesTrait;
    use EventServicesTrait;
    use EventStepServicesTrait;
    use FixturesServicesTrait;
    use LegacyCalendarServicesTrait;
    use MessagingServicesTrait;
    use RacerServicesTrait;
    use RandomnessServicesTrait;
    use ResultServicesTrait;
    use SearchEventServicesTrait;
    use SearchEventStepServicesTrait;
    use StandingServicesTrait;
    use StepTypeServicesTrait;
    use TeamServicesTrait;
    use TimeServicesTrait;
    use VenueServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
