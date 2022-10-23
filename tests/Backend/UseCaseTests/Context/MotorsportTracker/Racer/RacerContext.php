<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Racer;

use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class RacerContext extends MotorsportTrackerContext
{
    protected ?GetAllRacersForDateTimeResponse $racersResponse = null;

    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->serviceContainer()->racerRepositorySpy()->clear();
    }

    /**
     * @Given the racer for :racer exists
     *
     * @throws Exception
     */
    public function theRacerDataExists(string $racer): void
    {
        self::container()->fixtureLoader()->loadFixture("motorsport.racer.racer.{$this->format($racer)}");
    }

    /**
     * @When a client views the racers in season :season on date :date
     */
    public function aClientViewsTheRacers(string $season, string $date): void
    {
        $this->racersResponse  = null;
        $this->thrownException = null;

        try {
            $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");

            /** @var GetAllRacersForDateTimeResponse $response */
            $response = self::container()->queryBus()->ask(
                GetAllRacersForDateTimeQuery::fromScalars($date, $seasonId),
            );

            $this->racersResponse = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the racer data for :driver is created
     */
    public function theRacerDataIsCreated(string $driver): void
    {
        Assert::assertTrue(self::container()->serviceContainer()->racerRepositorySpy()->hasOneForDriver(
            $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}")
        ));
    }

    /**
     * @Then the racer data for :driver in car :car is from :startDate to :endDate
     */
    public function theRacerDataIsSplit(string $driver, string $car, string $startDate, string $endDate): void
    {
        $racers = self::container()->serviceContainer()->racerRepositorySpy()->findForDriverAndCar(
            $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}"),
            $this->fixtureId("motorsport.car.car.{$this->format($car)}"),
        );

        Assert::assertCount(1, $racers);

        $racer = array_shift($racers);

        Assert::assertEquals($startDate, $racer?->startDate()->value()->format('Y-m-d H:i:s'));
        Assert::assertEquals($endDate, $racer?->endDate()->value()->format('Y-m-d H:i:s'));
    }

    /**
     * @Then /^the two racer views are returned$/
     */
    public function racerViewsAreReturned(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->racersResponse);

        Assert::assertCount(2, $this->racersResponse->racers());
    }

    /**
     * @Then /^no racer views are returned$/
     */
    public function noRacerViewsAreReturned(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->racersResponse);

        Assert::assertEmpty($this->racersResponse->racers());
    }
}
