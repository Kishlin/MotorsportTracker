<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Event;

use Behat\Gherkin\Node\TableNode;
use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class CalendarContext extends MotorsportTrackerContext
{
    private ?ViewCalendarResponse $response;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @Given /^there are no events planned$/
     */
    public function thereAreNoEventsPlanned(): void
    {
    }

    /**
     * @When a client views the calendar for :month :year
     *
     * @throws Exception
     */
    public function aClientViewsTheCalendar(string $month, int $year): void
    {
        $response = self::container()->queryBus()->ask(ViewCalendarQuery::fromScalars($month, $year));

        assert($response instanceof ViewCalendarResponse);

        $this->response = $response;
    }

    /**
     * @Then /^a calendar is viewed with events$/
     */
    public function aCalendarIsViewedWithEvents(TableNode $expectedCalendar): void
    {
        Assert::assertNotNull($this->response);

        /** @var array<array{dateTime: string, championship: string, venue: string, type: string, event: string}> $expected */
        $expected = $expectedCalendar;

        $actual = $this->response->calendarView()->toArray();

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

    /**
     * @Then /^an empty calendar is viewed$/
     */
    public function anEmptyCalendarIsViewed(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertEmpty($this->response->calendarView()->toArray());
    }
}
