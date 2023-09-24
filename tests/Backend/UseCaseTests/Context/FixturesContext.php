<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

final class FixturesContext extends MotorsportTrackerContext
{
    /**
     * @BeforeScenario
     */
    public static function prepare(): void
    {
        self::container()->objectStoreSpy()->resetState();
        self::container()->cacheItemPoolSpy()->clear();
    }
}
