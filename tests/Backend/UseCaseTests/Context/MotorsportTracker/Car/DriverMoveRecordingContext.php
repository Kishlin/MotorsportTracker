<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Car;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\DriverMoveRecordingFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\RecordDriverMoveCommand;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveCarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class DriverMoveRecordingContext extends MotorsportTrackerContext
{
    private const DATE = '1993-11-22';

    private ?DriverMoveId $driverMoveId = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->driverMoveRepositorySpy()->clear();
    }

    /**
     * @Given /^a driver move exists for the driver and date$/
     * @Given /^a driver move exists for the car and date$/
     */
    public function aDriverExists(): void
    {
        self::container()->driverMoveRepositorySpy()->add(DriverMove::instance(
            new DriverMoveId(self::DRIVER_MOVE_ID),
            new DriverMoveDriverId(self::DRIVER_ID),
            new DriverMoveCarId(self::CAR_ID),
            new DriverMoveDate(new DateTimeImmutable(self::DATE)),
        ));
    }

    /**
     * @When /^a client records a driver move for the driver and car$/
     * @When /^a client records a driver move with the same driver and date$/
     * @When /^a client records a driver move with the same car and date$/
     * @When /^a client records a driver move for a missing driver$/
     * @When /^a client records a driver move for a missing car$/
     */
    public function aClientCreatesADriver(): void
    {
        $this->driverMoveId    = null;
        $this->thrownException = null;

        try {
            /** @var DriverMoveId $driverMoveId */
            $driverMoveId = self::container()->commandBus()->execute(
                RecordDriverMoveCommand::fromScalars(self::CAR_ID, self::DRIVER_ID, new DateTimeImmutable(self::DATE)),
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
