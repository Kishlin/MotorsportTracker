<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportTracker\Calendar;

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
        self::cacheDatabase()->loadFixture("motorsport.event.eventStep.{$this->format($eventStep)}");
    }

    /**
     * @Given the calendar event view :view exists
     */
    public function theCalendarEventViewExists(string $view): void
    {
        self::cacheDatabase()->loadFixture("motorsport.calendar.calendarEventStepView.{$this->format($view)}");
    }

    /**
     * @When a client views the calendar from :start to :end
     *
     * @throws Exception
     */
    public function aClientViewsTheCalendar(string $start, string $end): void
    {
        $this->response = self::handle(Request::create("/api/v1/calendar/view/{$start}/{$end}", 'GET'));
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

        /** @var array<array{championship: string, color: string, icon: string, name: string, venue: string, type: string, dateTime: string, reference: string}> $expected */
        $expected = $expectedCalendar;

        $actual = json_decode($responseContent, true);
        Assert::assertIsArray($actual);

        foreach ($expected as $expectedCalendarEntry) {
            $date = substr($expectedCalendarEntry['dateTime'], 0, 10);

            Assert::assertArrayHasKey($date, $actual);

            Assert::assertSame(
                $expectedCalendarEntry['championship'],
                $actual[$date][$expectedCalendarEntry['dateTime']]['championship_slug'],
            );

            Assert::assertSame(
                $expectedCalendarEntry['color'],
                $actual[$date][$expectedCalendarEntry['dateTime']]['color'],
            );

            Assert::assertSame(
                $expectedCalendarEntry['icon'],
                $actual[$date][$expectedCalendarEntry['dateTime']]['icon'],
            );

            Assert::assertSame(
                $expectedCalendarEntry['name'],
                $actual[$date][$expectedCalendarEntry['dateTime']]['name'],
            );

            Assert::assertSame(
                $expectedCalendarEntry['venue'],
                $actual[$date][$expectedCalendarEntry['dateTime']]['venue_label'],
            );

            Assert::assertSame(
                $expectedCalendarEntry['type'],
                $actual[$date][$expectedCalendarEntry['dateTime']]['type'],
            );

            Assert::assertArrayHasKey($expectedCalendarEntry['dateTime'], $actual[$date]);
        }
    }
}
