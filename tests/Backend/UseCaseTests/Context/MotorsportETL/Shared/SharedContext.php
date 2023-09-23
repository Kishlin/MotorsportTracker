<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportETL\Shared;

use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class SharedContext extends MotorsportTrackerContext
{
    /**
     * @BeforeScenario
     */
    public static function prepare(): void
    {
        self::container()->cachableConnectorSpy()->resetState();
        self::container()->entityStoreSpy()->resetState();
    }

    /**
     * @Then the external data source is called :count times
     */
    public function theExternalDataSourceIsCalledXTimes(int $count): void
    {
        Assert::assertSame($count, self::container()->cachableConnectorSpy()->actualRequestsCount());
    }

    /**
     * @Then it saved :count new :location
     */
    public function theTwoSeasonsFromTheDataSourceAreSaved(int $count, string $location): void
    {
        Assert::assertSame($count, self::container()->entityStoreSpy()->saved($location));
    }
}
