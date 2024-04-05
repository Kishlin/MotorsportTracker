<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

abstract class MotorsportTrackerContext implements Context
{
    private static ?TestServiceContainer $container = null;

    public function formatFixtureName(string $parameterValue): string
    {
        return strtolower(str_replace(' ', '_', $parameterValue));
    }

    public function formatCacheKey(string $cacheKey): string
    {
        return strtolower(str_replace(' ', '-', $cacheKey));
    }

    public function fixtureId(string $fixture): string
    {
        return self::container()->coreFixtureLoader()->identifier($fixture);
    }

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
