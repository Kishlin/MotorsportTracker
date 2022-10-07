<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\CarRegistrationFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\RegisterCarCommand;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarTeamId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class CarRegistrationContext extends MotorsportTrackerContext
{
    private const CAR_NUMBER       = 1;
    private const CAR_NUMBER_ALT   = 33;
    private const CAR_OTHER_NUMBER = 11;

    private ?CarId $carId               = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->carRepositorySpy()->clear();
    }

    /**
     * @Given /^a car exists for the team and season$/
     */
    public function aCarExists(): void
    {
        self::container()->carRepositorySpy()->add(Car::instance(
            new CarId(self::CAR_ID),
            new CarTeamId(self::TEAM_ID),
            new CarSeasonId(self::SEASON_ID),
            new CarNumber(self::CAR_NUMBER),
        ));
    }

    /**
     * @Given /^another car exists for the other team and season$/
     */
    public function anotherCarExists(): void
    {
        self::container()->carRepositorySpy()->add(Car::instance(
            new CarId(self::CAR_ID_ALT),
            new CarTeamId(self::TEAM_ID_ALT),
            new CarSeasonId(self::SEASON_ID),
            new CarNumber(self::CAR_NUMBER_ALT),
        ));
    }

    /**
     * @Given /^another car exists for another driver$/
     */
    public function yetAnotherCarExists(): void
    {
        self::container()->carRepositorySpy()->add(Car::instance(
            new CarId(self::CAR_OTHER_ID),
            new CarTeamId(self::TEAM_ID),
            new CarSeasonId(self::SEASON_ID),
            new CarNumber(self::CAR_OTHER_NUMBER),
        ));
    }

    /**
     * @When /^a client registers a car for the team and season$/
     * @When /^a client registers a car with the same number$/
     * @When /^a client registers a car for a missing team$/
     * @When /^a client registers a car for a missing season$/
     */
    public function aClientCreatesADriver(): void
    {
        $this->carId           = null;
        $this->thrownException = null;

        try {
            /** @var CarId $carId */
            $carId = self::container()->commandBus()->execute(
                RegisterCarCommand::fromScalars(self::CAR_NUMBER, self::TEAM_ID, self::SEASON_ID),
            );

            $this->carId = $carId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the car is saved$/
     */
    public function theCarIsRegistered(): void
    {
        Assert::assertNotNull($this->carId);
        Assert::assertTrue(self::container()->carRepositorySpy()->has($this->carId));
    }

    /**
     * @Then /^the car registration is declined$/
     */
    public function theCarRegistrationIsDeclined(): void
    {
        Assert::assertNull($this->carId);
        Assert::assertInstanceOf(CarRegistrationFailureException::class, $this->thrownException);
    }
}
