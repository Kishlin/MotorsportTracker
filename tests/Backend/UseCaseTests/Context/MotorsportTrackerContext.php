<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

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
        return self::container()->coreFixtureLoader()->identifier($fixture) ?? self::container()->uuidGenerator()->uuid4();
    }

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
