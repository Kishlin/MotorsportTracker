<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\CarNotfoundException;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarQuery;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchCarContext extends MotorsportTrackerContext
{
    private ?SearchCarResponse $response = null;
    private ?Throwable $thrownException  = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @When a client searches for the car :number of :team for :championship in :year
     */
    public function aClientSearchesForTheCar(int $number, string $team, string $championship, int $year): void
    {
        $this->response        = null;
        $this->thrownException = null;

        try {
            $response = self::container()->queryBus()->ask(
                SearchCarQuery::fromScalars($number, $team, $championship, $year),
            );

            assert($response instanceof SearchCarResponse);

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the id of the car :car is returned
     */
    public function theIdOfTheCarIsReturned(string $car): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->response);

        Assert::assertSame(
            $this->fixtureId("motorsport.car.car.{$this->format($car)}"),
            $this->response->carId()->value(),
        );
    }

    /**
     * @Then /^it does not receive any car id$/
     */
    public function itDoesNotReceiveAnyCarId(): void
    {
        Assert::assertNull($this->response);

        Assert::assertInstanceOf(CarNotfoundException::class, $this->thrownException);
    }
}
