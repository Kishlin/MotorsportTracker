<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportCache\Calendar;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ViewCalendarEventsContext extends BackendApiContext
{
    private Response $response;

    #[Given('there are no events planned')]
    public function thereAreNoEventsPlanned(): void
    {
    }

    #[Given('the calendar event :view exists')]
    public function theCalendarEventViewExists(string $view): void
    {
        self::cacheDatabase()->loadFixture("motorsport.calendar.calendarEvent.{$this->format($view)}");
    }

    /**
     * @throws Exception
     */
    #[When('a client views the calendar from :start to :end')]
    public function aClientViewsTheCalendar(string $start, string $end): void
    {
        $this->response = self::handle(Request::create("/api/v1/calendar/view/{$start}/{$end}", 'GET'));
    }

    #[Then('an empty calendar is viewed')]
    public function anEmptyCalendarIsViewed(): void
    {
        $responseContent = $this->response->getContent();

        Assert::assertNotFalse($responseContent);
        Assert::assertSame([], json_decode($responseContent, true));
    }

    #[Then('a calendar is viewed with events')]
    public function aCalendarIsViewedWithEvents(TableNode $expectedCalendar): void
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        /** @var array<array{key: string, count: int, slug: string}> $expected */
        $expected = $expectedCalendar;

        $actual = json_decode($responseContent, true);
        Assert::assertIsArray($actual);

        foreach ($expected as $expectedCalendarEntry) {
            $key = $expectedCalendarEntry['key'];

            Assert::assertArrayHasKey($key, $actual);

            Assert::assertCount((int) $expectedCalendarEntry['count'], $actual[$key]);

            Assert::assertSame($expectedCalendarEntry['slug'], $actual[$key][0]['slug']);
        }
    }
}
