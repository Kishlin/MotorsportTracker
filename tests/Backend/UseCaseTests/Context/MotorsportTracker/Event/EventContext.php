<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\CreateEventCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\EventCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\SeasonHasEventWithIndexOrVenueException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventContext extends MotorsportTrackerContext
{
    private const EVENT_INDEX     = 0;
    private const EVENT_INDEX_ALT = 1;
    private const EVENT_LABEL     = 'Event';
    private const EVENT_LABEL_ALT = 'Event Alt';

    private ?EventId $eventId           = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventRepositorySpy()->clear();
    }

    /**
     * @Given /^an event exists for the season and index$/
     */
    public function aEventExistsForTheSeasonAndIndex(): void
    {
        self::container()->eventRepositorySpy()->add(Event::instance(
            new EventId(self::EVENT_ID),
            new EventSeasonId(self::SEASON_ID),
            new EventVenueId(self::VENUE_ID),
            new EventIndex(self::EVENT_INDEX),
            new EventLabel(self::EVENT_LABEL_ALT),
        ));
    }

    /**
     * @Given /^an event exists for the season and label/
     */
    public function aEventExistsForTheSeasonAndLabel(): void
    {
        self::container()->eventRepositorySpy()->add(Event::instance(
            new EventId(self::EVENT_ID),
            new EventSeasonId(self::SEASON_ID),
            new EventVenueId(self::VENUE_ID),
            new EventIndex(self::EVENT_INDEX_ALT),
            new EventLabel(self::EVENT_LABEL),
        ));
    }

    /**
     * @When /^a client creates a new event for the season venue index and label$/
     * @When /^a client creates an event for the same season and index$/
     * @When /^a client creates an event for the same season and label$/
     */
    public function aClientCreatesAnEvent(): void
    {
        $this->eventId         = null;
        $this->thrownException = null;

        try {
            /** @var EventId $eventId */
            $eventId = self::container()->commandBus()->execute(
                CreateEventCommand::fromScalars(self::SEASON_ID, self::VENUE_ID, self::EVENT_INDEX, self::EVENT_LABEL),
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
