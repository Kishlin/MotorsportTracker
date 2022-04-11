<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

abstract class MotorsportTrackerContext implements Context
{
    protected const CHAMPIONSHIP_ID = '7c5ff11b-277f-4ca5-bc27-b71d83ce3b39';
    protected const SEASON_ID       = 'c04ae37a-1c4c-42aa-b3f1-3ab8ec31ee72';

    private static ?TestServiceContainer $container = null;

    /**
     * @AfterScenario
     */
    abstract public function clearGatewaySpies(): void;

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
