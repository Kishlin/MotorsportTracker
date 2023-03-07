<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Calendar;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class ViewCalendarEventsContext extends MotorsportTrackerContext
{
    private ?ViewCalendarEventsResponse $response;

    public function clearGatewaySpies(): void
    {
    }

    #[Given('there are no events planned')]
    public function thereAreNoEventsPlanned(): void
    {
    }

    /**
     * @throws Exception
     */
    #[Given('the calendar event :name exists')]
    public function theCalendarEventViewExists(string $name): void
    {
        self::container()->cacheFixtureLoader()->loadFixture("motorsport.calendar.calendarEvent.{$this->format($name)}");
    }

    /**
     * @throws Exception
     */
    #[When('a client views the calendar from :start to :end')]
    public function aClientViewsTheCalendar(string $start, string $end): void
    {
        $response = self::container()->queryBus()->ask(ViewCalendarEventsQuery::fromScalars($start, $end));

        assert($response instanceof ViewCalendarEventsResponse);

        $this->response = $response;
    }

    #[Then('a calendar is viewed with events')]
    public function aCalendarIsViewedWithEvents(TableNode $expectedCalendar): void
    {
        Assert::assertNotNull($this->response);

        /** @var array<array{key: string, count: int, slug: string}> $expected */
        $expected = $expectedCalendar;

        $actual = $this->response->calendarView()->toArray();

        foreach ($expected as $expectedCalendarEntry) {
            $key = $expectedCalendarEntry['key'];

            Assert::assertArrayHasKey($key, $actual);

            Assert::assertCount((int) $expectedCalendarEntry['count'], $actual[$key]);

            Assert::assertSame($expectedCalendarEntry['slug'], $actual[$key][0]['slug']);
        }
    }

    #[Then('an empty calendar is viewed')]
    public function anEmptyCalendarIsViewed(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertEmpty($this->response->calendarView()->toArray());
    }
}
