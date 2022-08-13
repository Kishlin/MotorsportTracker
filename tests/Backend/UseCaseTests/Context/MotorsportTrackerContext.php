<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

abstract class MotorsportTrackerContext implements Context
{
    protected const COUNTRY_ID = '883bf45c-f7ca-4a34-86ed-79287139f16e';

    protected const CAR_ID         = '8da8665a-be40-4482-af48-314b100e12e3';
    protected const CAR_ID_ALT     = '0ef65d22-f94a-446b-ac67-7881785885d2';
    protected const DRIVER_MOVE_ID = 'c2f27d0a-5f18-4015-bf7d-e1c58dba61a5';

    protected const CHAMPIONSHIP_ID = '7c5ff11b-277f-4ca5-bc27-b71d83ce3b39';
    protected const SEASON_ID       = 'c04ae37a-1c4c-42aa-b3f1-3ab8ec31ee72';

    protected const DRIVER_ID = 'e36d650b-6d98-4fc5-a0a6-f4842f4bdf73';

    protected const RACER_ID = '53491a72-402e-42c8-9198-e09e7048eab9';

    protected const TEAM_ID     = 'a66f90be-6790-4a65-b864-b248dc6cde22';
    protected const TEAM_ID_ALT = 'b84b7e44-dc65-435a-8558-8da865ba163a';

    protected const VENUE_ID = '577bb90b-3087-4b8c-a6bc-c70125c254eb';

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
