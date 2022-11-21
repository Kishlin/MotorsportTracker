<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Racer;

use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate\UpdateRacerEndDateCommand;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class RacerUpdateContext extends MotorsportTrackerContext
{
    private ?Throwable $thrownException;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @When a client wants the racer for :driver in championship :championship to end on :dateTime
     */
    public function aClientWantsTheRacerToEndOn(string $driver, string $championship, string $dateTime): void
    {
        $this->thrownException = null;

        try {
            $driverId = $this->fixtureId("motorsport.driver.driver.{$this->format($driver)}");

            self::container()->commandBus()->execute(
                UpdateRacerEndDateCommand::fromScalars($driverId, $championship, $dateTime),
            );
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the racer for :racer now ends on :dateTime
     */
    public function theRacerNowEndsOn(string $racer, string $dateTime): void
    {
        Assert::assertNull($this->thrownException);

        $racer = self::container()->racerRepositorySpy()->get(
            new RacerId($this->fixtureId("motorsport.racer.racer.{$this->format($racer)}")),
        );

        Assert::assertNotNull($racer);

        Assert::assertSame($dateTime, $racer->endDate()->value()->format('Y-m-d H:i:s'));
    }

    /**
     * @Then /^the request to update the racer is rejected$/
     */
    public function theRequestToUpdateTheRacerHasBeenRejected(): void
    {
        Assert::assertNotNull($this->thrownException);
    }
}
