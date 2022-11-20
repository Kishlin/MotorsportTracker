<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\EventNotFoundException;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchEventContext extends MotorsportTrackerContext
{
    private ?SearchEventResponse $response = null;

    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @When a client searches for the event for season :season with keyword :keyword
     */
    public function aClientSearchesForTheEvent(string $season, string $keyword): void
    {
        $this->response        = null;
        $this->thrownException = null;

        $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");

        try {
            /** @var SearchEventResponse $response */
            $response = self::container()->queryBus()->ask(
                SearchEventQuery::fromScalars($seasonId, $keyword),
            );

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the id of the event :event is returned
     */
    public function theIdOfTheEventIsReturned(string $event): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->response);

        Assert::assertSame(
            $this->fixtureId("motorsport.event.event.{$this->format($event)}"),
            $this->response->eventId()->value(),
        );
    }

    /**
     * @Then /^it does not receive any event id$/
     */
    public function itDoesNotReceiveAnyEventId(): void
    {
        Assert::assertNull($this->response);
        Assert::assertInstanceOf(EventNotFoundException::class, $this->thrownException);
    }
}
