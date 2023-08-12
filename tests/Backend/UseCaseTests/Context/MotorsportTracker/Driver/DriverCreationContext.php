<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Driver;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\CreateDriverIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class DriverCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $driverId  = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->driverRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the driver :name exists')]
    public function theDriverExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.driver.driver.{$this->format($name)}");
    }

    #[When('a client creates the driver :name with code :code for the country :country')]
    #[When('a client creates a driver with same name')]
    public function aClientCreatesADriver(string $name = 'Max Verstappen', string $code = 'VER'): void
    {
        $this->driverId        = null;
        $this->thrownException = null;

        try {
            /** @var UuidValueObject $driverId */
            $driverId = self::container()->commandBus()->execute(
                CreateDriverIfNotExistsCommand::fromScalars($name, $code, null, null),
            );

            $this->driverId = $driverId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the driver is saved')]
    #[Then('the driver is not duplicated')]
    public function theDriverIsSaved(): void
    {
        Assert::assertCount(1, self::container()->driverRepositorySpy()->all());

        Assert::assertNotNull($this->driverId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->driverRepositorySpy()->has($this->driverId));
    }

    #[Then('the id :driver is returned')]
    #[Then('the id of :driver is returned')]
    public function theIdOfTheDriverIsReturned(string $driver): void
    {
        Assert::assertNotNull($this->driverId);
        Assert::assertNull($this->thrownException);

        Assert::assertSame(
            $driver,
            self::container()->driverRepositorySpy()->safeGet($this->driverId)->name()->value(),
        );
    }
}
