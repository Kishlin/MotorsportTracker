<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Car;

use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\CarRegistrationFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\RegisterCarCommand;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class CarRegistrationContext extends MotorsportTrackerContext
{
    private ?CarId $carId               = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->carRepositorySpy()->clear();
    }

    /**
     * @Given the car :car exists
     *
     * @throws Exception
     */
    public function theCarExists(string $car): void
    {
        self::container()->fixtureLoader()->loadFixture("motorsport.car.car.{$this->format($car)}");
    }

    /**
     * @When a client registers the car :number for the team :team and season :season
     * @When /^a client registers a car with the same number$/
     * @When /^a client registers a car for a missing team$/
     * @When /^a client registers a car for a missing season$/
     */
    public function aClientCreatesADriver(int $number = 1, string $team = 'Red Bull Racing', string $season = 'Formula One 2022'): void
    {
        $this->carId           = null;
        $this->thrownException = null;

        try {
            $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");
            $teamId   = $this->fixtureId("motorsport.team.team.{$this->format($team)}");

            /** @var CarId $carId */
            $carId = self::container()->commandBus()->execute(
                RegisterCarCommand::fromScalars($number, $teamId, $seasonId),
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
