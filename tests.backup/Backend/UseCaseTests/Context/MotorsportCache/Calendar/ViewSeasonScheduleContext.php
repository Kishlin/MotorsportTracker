<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Calendar;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleResponse;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class ViewSeasonScheduleContext extends MotorsportTrackerContext
{
    private ?ViewSeasonScheduleResponse $response;

    public function clearGatewaySpies(): void
    {
    }

    /**
     * @throws Exception
     */
    #[When('a client views the schedule for :championship :year')]
    public function aClientViewsTheCalendar(string $championship, int $year): void
    {
        $response = self::container()->queryBus()->ask(ViewSeasonScheduleQuery::fromScalars($championship, $year));

        assert($response instanceof ViewSeasonScheduleResponse);

        $this->response = $response;
    }

    #[Then('a schedule is viewed with events')]
    public function aScheduleIsViewedWithEvents(TableNode $expectedSchedule): void
    {
        Assert::assertNotNull($this->response);

        /** @var array<array{key: string, count: int, slug: string}> $expected */
        $expected = $expectedSchedule;

        $actual = $this->response->schedule()->toArray();

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
        Assert::assertNotNull($this->response);
        Assert::assertEmpty($this->response->schedule()->toArray());
    }
}
