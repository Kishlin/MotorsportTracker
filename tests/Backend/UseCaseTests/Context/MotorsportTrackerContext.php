<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

abstract class MotorsportTrackerContext implements Context
{
    private static ?TestServiceContainer $container = null;

    /**
     * @AfterScenario
     */
    abstract public function clearGatewaySpies(): void;

    public function format(string $parameterValue): string
    {
        return lcfirst(str_replace(' ', '', $parameterValue));
    }

    public function fixtureId(string $fixture): string
    {
        try {
            return self::container()->coreFixtureLoader()->identifier($fixture);
        } catch (RuntimeException) {
            return self::container()->cacheFixtureLoader()->identifier($fixture);
        }
    }

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
