<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

abstract class MotorsportTrackerContext implements Context
{
    protected const COUNTRY_ID = '883bf45c-f7ca-4a34-86ed-79287139f16e';

    protected const CHAMPIONSHIP_ID = '7c5ff11b-277f-4ca5-bc27-b71d83ce3b39';
    protected const SEASON_ID       = 'c04ae37a-1c4c-42aa-b3f1-3ab8ec31ee72';

    protected const DRIVER_ID = 'e36d650b-6d98-4fc5-a0a6-f4842f4bdf73';

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
