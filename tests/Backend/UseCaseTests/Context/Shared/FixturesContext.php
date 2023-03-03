<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\Shared;

use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;

final class FixturesContext extends MotorsportTrackerContext
{
    public function clearGatewaySpies(): void
    {
    }

    /**
     * @AfterScenario
     */
    public function cleanUpFixtures(): void
    {
        self::container()->coreFixtureLoader()->reset();
    }

    /**
     * @Given the :fixture :name does not exist yet
     */
    public function theFixtureDoesNotExistYet(): void
    {
    }
}
