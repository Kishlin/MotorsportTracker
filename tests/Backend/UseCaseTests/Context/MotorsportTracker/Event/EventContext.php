<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\CreateEventIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\EventCreationFailureException;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class EventContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $eventId   = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->eventRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the event :event exists')]
    public function theEventExists(string $event): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.event.event.{$this->format($event)}");
    }

    #[When('a client creates the event :slug of index :index for the season :season and venue :venue')]
    #[When('a client creates an event for the same season and index with slug :slug')]
    #[When('a client creates an event for the same season and slug with index :index')]
    public function aClientCreatesAnEvent(
        string $slug = 'Dutch GP',
        int $index = 14,
        string $season = 'formulaOne2022',
        string $venue = 'Zandvoort',
    ): void {
        $this->eventId         = null;
        $this->thrownException = null;

        try {
            $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");
            $venueId  = $this->fixtureId("motorsport.venue.venue.{$this->format($venue)}");

            /** @var UuidValueObject $eventId */
            $eventId = self::container()->commandBus()->execute(
                CreateEventIfNotExistsCommand::fromScalars($seasonId, $venueId, $index, $slug, $slug, $slug, null, null),
            );

            $this->eventId = $eventId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the event is saved')]
    #[Then('the id of the event is returned')]
    public function theEventIsSaved(): void
    {
        Assert::assertNotNull($this->eventId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->eventRepositorySpy()->has($this->eventId));
    }

    #[Then('the event creation with the same index is declined')]
    #[Then('the event creation with the same slug is declined')]
    public function theEventCreationIsDeclined(): void
    {
        Assert::assertNull($this->eventId);
        Assert::assertInstanceOf(EventCreationFailureException::class, $this->thrownException);
    }
}
