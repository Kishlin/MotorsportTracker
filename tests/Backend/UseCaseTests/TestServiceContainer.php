<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\Services\Country\CountryServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship\ChampionshipServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Driver\DriverServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team\TeamServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue\VenueServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Messaging\MessagingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Randomness\RandomnessServicesTrait;

final class TestServiceContainer
{
    use ChampionshipServicesTrait;
    use CountryServicesTrait;
    use DriverServicesTrait;
    use MessagingServicesTrait;
    use TeamServicesTrait;
    use RandomnessServicesTrait;
    use VenueServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
