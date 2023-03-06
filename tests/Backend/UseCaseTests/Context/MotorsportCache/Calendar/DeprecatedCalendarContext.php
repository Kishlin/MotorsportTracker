<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Calendar;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar\ViewCalendarResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class DeprecatedCalendarContext extends MotorsportTrackerContext
{
    private ?ViewCalendarResponse $response;

    public function clearGatewaySpies(): void
    {
        self::container()->calendarEventStepViewRepositorySpy()->clear();
    }

    #[Given('there are no events planned')]
    public function thereAreNoEventsPlanned(): void
    {
    }

    /**
     * @throws Exception
     */
    #[Given('the calendar event view :name exists')]
    public function theCalendarEventViewExists(string $name): void
    {
        self::container()->cacheFixtureLoader()->loadFixture("motorsport.calendar.calendarEventStepView.{$this->format($name)}");
    }

    /**
     * @throws Exception
     */
    #[When('a client views the calendar from :start to :end')]
    public function aClientViewsTheCalendar(string $start, string $end): void
    {
        $response = self::container()->queryBus()->ask(ViewCalendarQuery::fromScalars($start, $end));

        assert($response instanceof ViewCalendarResponse);

        $this->response = $response;
    }

    #[Then('a calendar is viewed with events')]
    public function aCalendarIsViewedWithEvents(TableNode $expectedCalendar): void
    {
        Assert::assertNotNull($this->response);

        /** @var array<array{championship: string, color: string, icon: string, name: string, venue: string, type: string, dateTime: string, reference: string}> $expected */
        $expected = $expectedCalendar;

        $actual = $this->response->calendarView()->toArray();

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

    #[Then('an empty calendar is viewed')]
    public function anEmptyCalendarIsViewed(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertEmpty($this->response->calendarView()->toArray());
    }
}
