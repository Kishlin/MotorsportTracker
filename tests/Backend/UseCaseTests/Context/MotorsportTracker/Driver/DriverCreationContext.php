<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Driver;

use Exception;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommand;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class DriverCreationContext extends MotorsportTrackerContext
{
    private ?DriverId $driverId         = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->driverRepositorySpy()->clear();
    }

    /**
     * @Given the driver :name exists
     *
     * @throws Exception
     */
    public function theDriverExists(string $name): void
    {
        self::container()->fixtureLoader()->loadFixture("motorsport.driver.driver.{$this->format($name)}");
    }

    /**
     * @When a client creates the driver :firstname :lastname for the country :country
     * @When /^a client creates a driver with same firstname and name$/
     * @When /^a client creates a driver for a missing country$/
     */
    public function aClientCreatesADriver(string $firstname = 'Max', string $lastname = 'Verstappen', string $country = 'Netherlands'): void
    {
        $this->driverId        = null;
        $this->thrownException = null;

        try {
            $countryId = $this->fixtureId("country.country.{$this->format($country)}");

            /** @var DriverId $driverId */
            $driverId = self::container()->commandBus()->execute(
                CreateDriverCommand::fromScalars($lastname, $firstname, $countryId),
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
