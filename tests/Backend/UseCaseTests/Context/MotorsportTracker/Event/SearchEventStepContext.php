<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\EventStepNotFoundException;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class SearchEventStepContext extends MotorsportTrackerContext
{
    private ?SearchEventStepIdAndDateTimeResponse $response = null;

    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @When a client searches for the :type step for season :season with keyword :keyword
     */
    public function aClientSearchesForTheStep(string $type, string $season, string $keyword): void
    {
        $this->response        = null;
        $this->thrownException = null;

        $seasonId = $this->fixtureId("motorsport.championship.season.{$this->format($season)}");

        try {
            /** @var SearchEventStepIdAndDateTimeResponse $response */
            $response = self::container()->queryBus()->ask(
                SearchEventStepIdAndDateTimeQuery::fromScalars($seasonId, $keyword, $type),
            );

            $this->response = $response;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then the id and date time of the event step :eventStep are returned
     */
    public function theIdAndDateTimeOfTheEventStepAreReturned(string $eventStep): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->response);

        Assert::assertSame(
            $this->fixtureId("motorsport.event.eventStep.{$this->format($eventStep)}"),
            $this->response->eventStep()->eventStepId(),
        );
    }

    /**
     * @Then /^it does not receive any event step info$/
     */
    public function itDoesNotReceiveAnyEventStepInfo(): void
    {
        Assert::assertNull($this->response);
        Assert::assertInstanceOf(EventStepNotFoundException::class, $this->thrownException);
    }
}
