<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportCache\Schedule;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ViewSeasonScheduleContext extends BackendApiContext
{
    private Response $response;

    /**
     * @throws Exception
     */
    #[When('a client views the schedule for :championship :year')]
    public function aClientViewsTheCalendar(string $championship, int $year): void
    {
        $this->response = self::handle(Request::create("/api/v1/schedule/view/{$championship}/{$year}", 'GET'));
    }

    #[Then('a schedule is viewed with events')]
    public function aCalendarIsViewedWithEvents(TableNode $expectedSchedule): void
    {
        $responseContent = $this->response->getContent();
        Assert::assertNotFalse($responseContent);

        /** @var array<array{key: string, count: int, slug: string}> $expected */
        $expected = $expectedSchedule;

        $actual = json_decode($responseContent, true);
        Assert::assertIsArray($actual);

        foreach ($expected as $expectedScheduleEntry) {
            $key = $expectedScheduleEntry['key'];

            Assert::assertArrayHasKey($key, $actual);

            Assert::assertCount((int) $expectedScheduleEntry['count'], $actual[$key]);

            Assert::assertSame($expectedScheduleEntry['slug'], $actual[$key][0]['slug']);
        }
    }

    #[Then('an empty schedule is viewed')]
    public function anEmptyScheduleIsViewed(): void
    {
        $responseContent = $this->response->getContent();

        Assert::assertNotFalse($responseContent);
        Assert::assertSame([], json_decode($responseContent, true));
    }
}
