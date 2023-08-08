<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Result;

use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\ComputeEventResultsByRaceCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ResultContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $raceResultId = null;
    private ?Throwable $thrownException    = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventResultsByRaceRepositorySpy()->clear();
    }

    #[When('a client computes results for the event :event')]
    public function aClientComputesResultsForEvent(string $event): void
    {
        $this->raceResultId    = null;
        $this->thrownException = null;

        $eventId = $this->fixtureId("motorsport.event.event.{$this->format($event)}");

        try {
            $raceResultId = self::container()->commandBus()->execute(
                ComputeEventResultsByRaceCommand::fromScalars($eventId),
            );

            assert($raceResultId instanceof UuidValueObject);

            $this->raceResultId = $raceResultId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the race results for event :event are computed')]
    public function theRaceResultForEventAreComputed(string $event): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->raceResultId);

        $eventId = $this->fixtureId("motorsport.event.event.{$this->format($event)}");

        Assert::assertTrue(self::container()->eventResultsByRaceRepositorySpy()->has($this->raceResultId));

        $eventResultsByRace = self::container()->eventResultsByRaceRepositorySpy()->safeGet($this->raceResultId);
        Assert::assertSame($eventId, $eventResultsByRace->event()->value());
    }

    #[Then('there is a result for :driver in race :race position :position')]
    public function thereIsAResultForDriverInRace(string $driver, int $race, int $position): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->raceResultId);

        Assert::assertTrue(self::container()->eventResultsByRaceRepositorySpy()->has($this->raceResultId));

        $eventResultsByRace = self::container()->eventResultsByRaceRepositorySpy()->safeGet($this->raceResultId);

        $results = $eventResultsByRace->resultsByRace()->data();

        Assert::assertSame($driver, $results[$race - 1]['result'][$position - 1]['driver']['name']);
    }
}
