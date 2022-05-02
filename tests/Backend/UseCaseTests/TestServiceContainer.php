<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\Services\Country\CountryServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship\ChampionshipServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Messaging\MessagingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\Randomness\RandomnessServicesTrait;

final class TestServiceContainer
{
    use CountryServicesTrait;
    use ChampionshipServicesTrait;

    use MessagingServicesTrait;
    use RandomnessServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
