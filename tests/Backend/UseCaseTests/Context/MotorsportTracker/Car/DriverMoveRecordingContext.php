<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Car;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\DriverMoveRecordingFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\RecordDriverMoveCommand;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class DriverMoveRecordingContext extends MotorsportTrackerContext
{
    private ?DriverMoveId $driverMoveId = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->driverMoveRepositorySpy()->clear();
    }

    /**
     * @Given the driver move :driverMove was already recorded
     *
     * @throws Exception
     */
    public function aDriverMoveExists(string $driverMove): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.car.driverMove.{$this->format($driverMove)}");
    }

    /**
     * @When a client records a driver move for driver :driver to the car :car on date :date
     * @When /^a client records a driver move with the same driver and date$/
     * @When /^a client records a driver move with the same car and date$/
     * @When /^a client records a driver move for a missing driver$/
     * @When /^a client records a driver move for a missing car$/
     */
    public function aClientRecordsADriverMove(
        string $driver = 'Max Verstappen',
        string $car = 'Red Bull Racing 2022 First Car',
        string $date = '2022-01-01 00:00:00'
    ): void {
        $this->driverMoveId    = null;
        $this->thrownException = null;

        try {
            $driverId = $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}");
            $carId    = $this->fixtureId("motorsport.car.car.{$this->format($car)}");

            /** @var DriverMoveId $driverMoveId */
            $driverMoveId = self::container()->commandBus()->execute(
                RecordDriverMoveCommand::fromScalars($carId, $driverId, new DateTimeImmutable($date)),
            );

            $this->driverMoveId = $driverMoveId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the driver move is recorded$/
     */
    public function theDriverMoveIsRecorded(): void
    {
        Assert::assertNotNull($this->driverMoveId);
        Assert::assertTrue(self::container()->driverMoveRepositorySpy()->has($this->driverMoveId));
    }

    /**
     * @Then /^the driver move recording is declined$/
     */
    public function theDriverMoveRecordingIsDeclined(): void
    {
        Assert::assertNull($this->driverMoveId);
        Assert::assertInstanceOf(DriverMoveRecordingFailureException::class, $this->thrownException);
    }
}
