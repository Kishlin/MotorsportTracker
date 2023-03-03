<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\CreateEventCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\SeasonHasEventWithIndexOrVenueException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventContext extends MotorsportTrackerContext
{
    private ?EventId $eventId           = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventRepositorySpy()->clear();
    }

    /**
     * @Given the event :event exists
     *
     * @throws Exception
     */
    public function theEventExists(string $event): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.event.event.{$this->format($event)}");
    }

    /**
     * @When a client creates the event :label of index :index for the season :season and venue :venue
     * @When a client creates an event for the same season and index with label :label
     * @When a client creates an event for the same season and label with index :index
     */
    public function aClientCreatesAnEvent(
        string $label = 'Dutch GP',
        int $index = 14,
        string $season = 'formulaOne2022',
        string $venue = 'Zandvoort',
    ): void {
        $this->eventId         = null;
        $this->thrownException = null;

        try {
            $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");
            $venueId  = $this->fixtureId("motorsport.venue.venue.{$this->format($venue)}");

            /** @var EventId $eventId */
            $eventId = self::container()->commandBus()->execute(
                CreateEventCommand::fromScalars($seasonId, $venueId, $index, $label),
            );

            $this->eventId = $eventId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the event is saved$/
     */
    public function theEventIsSaved(): void
    {
        Assert::assertNotNull($this->eventId);
        Assert::assertTrue(self::container()->eventRepositorySpy()->has($this->eventId));
    }

    /**
     * @Then /^the event creation with the same index is declined$/
     * @Then /^the event creation with the same label is declined$/
     */
    public function theEventCreationIsDeclined(): void
    {
        Assert::assertNull($this->eventId);
        Assert::assertInstanceOf(SeasonHasEventWithIndexOrVenueException::class, $this->thrownException);
    }
}
