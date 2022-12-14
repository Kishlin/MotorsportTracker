<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportTracker\Event;

use Behat\Gherkin\Node\TableNode;
use Exception;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CalendarContext extends BackendApiContext
{
    private Response $response;

    /**
     * @Given /^there are no events planned$/
     */
    public function thereAreNoEventsPlanned(): void
    {
    }

    /**
     * @Given the eventStep :eventStep exists
     */
    public function theEventStepExists(string $eventStep): void
    {
        self::database()->loadFixture("motorsport.event.eventStep.{$this->format($eventStep)}");
    }

    /**
     * @When a client views the calendar for :string :year
     *
     * @throws Exception
     */
    public function aClientViewsTheCalendar(string $month, int $year): void
    {
        $this->response = self::handle(Request::create("/events/calendar/{$month}/{$year}", 'GET'));
    }

    /**
     * @Then /^an empty calendar is viewed$/
     */
    public function anEmptyCalendarIsViewed(): void
    {
        $responseContent = $this->response->getContent();

        Assert::assertNotFalse($responseContent);
        Assert::assertSame([], json_decode($responseContent, true));
    }

    /**
     * @Then /^a calendar is viewed with events$/
     */
    public function aCalendarIsViewedWithEvents(TableNode $expectedCalendar): void
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        /** @var array<array{dateTime: string, championship: string, venue: string, type: string, event: string}> $expected */
        $expected = $expectedCalendar;

        $actual = json_decode($responseContent, true);
        Assert::assertIsArray($actual);

        foreach ($expected as $expectedCalendarEntry) {
            $date = substr($expectedCalendarEntry['dateTime'], 0, 10);

            Assert::assertArrayHasKey($date, $actual);

            Assert::assertArrayHasKey($expectedCalendarEntry['dateTime'], $actual[$date]);

            Assert::assertSame(
                self::fixtureId("motorsport.championship.championship.{$this->format($expectedCalendarEntry['championship'])}"),
                $actual[$date][$expectedCalendarEntry['dateTime']]['championship'],
            );

            Assert::assertSame(
                self::fixtureId("motorsport.venue.venue.{$this->format($expectedCalendarEntry['venue'])}"),
                $actual[$date][$expectedCalendarEntry['dateTime']]['venue'],
            );

            Assert::assertSame(
                self::fixtureId("motorsport.event.stepType.{$this->format($expectedCalendarEntry['type'])}"),
                $actual[$date][$expectedCalendarEntry['dateTime']]['type'],
            );

            Assert::assertSame(
                self::fixtureId("motorsport.event.event.{$this->format($expectedCalendarEntry['event'])}"),
                $actual[$date][$expectedCalendarEntry['dateTime']]['event'],
            );
        }
    }
}
