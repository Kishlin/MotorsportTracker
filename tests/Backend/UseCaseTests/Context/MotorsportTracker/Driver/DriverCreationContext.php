<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommand;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverCountryId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverFirstname;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class DriverCreationContext extends MotorsportTrackerContext
{
    private const DRIVER_FIRSTNAME = 'Max';
    private const DRIVER_NAME      = 'Verstappen';

    private ?DriverId $driverId         = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->driverRepositorySpy()->clear();
    }

    /**
     * @Given /^a driver exists for the country$/
     */
    public function aDriverExists(): void
    {
        self::container()->driverRepositorySpy()->add(Driver::instance(
            new DriverId(self::DRIVER_ID),
            new DriverFirstname(self::DRIVER_FIRSTNAME),
            new DriverName(self::DRIVER_NAME),
            new DriverCountryId(self::COUNTRY_ID),
        ));
    }

    /**
     * @When /^a client creates a new driver for the country$/
     * @When /^a client creates a driver with same firstname and name$/
     * @When /^a client creates a driver for a missing country$/
     */
    public function aClientCreatesADriver(): void
    {
        $this->driverId        = null;
        $this->thrownException = null;

        try {
            /** @var DriverId $driverId */
            $driverId = self::container()->commandBus()->execute(
                CreateDriverCommand::fromScalars(self::DRIVER_NAME, self::DRIVER_FIRSTNAME, self::COUNTRY_ID),
            );

            $this->driverId = $driverId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the driver is saved$/
     */
    public function theDriverIsSaved(): void
    {
        Assert::assertNotNull($this->driverId);
        Assert::assertTrue(self::container()->driverRepositorySpy()->has($this->driverId));
    }

    /**
     * @Then /^the driver creation is declined$/
     */
    public function theDriverCreationIsDeclined(): void
    {
        Assert::assertNull($this->driverId);
        Assert::assertInstanceOf(DriverCreationFailureException::class, $this->thrownException);
    }
}
