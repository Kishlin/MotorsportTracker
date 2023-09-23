<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

abstract class MotorsportTrackerContext implements Context
{
    private static ?TestServiceContainer $container = null;

    /**
     * @BeforeScenario
     */
    public static function prepare(): void
    {
        self::container()->cachableConnectorSpy()->resetState();
        self::container()->entityStoreSpy()->resetState();
    }

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
