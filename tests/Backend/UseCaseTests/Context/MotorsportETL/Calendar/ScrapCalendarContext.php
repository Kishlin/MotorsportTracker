<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportETL\Calendar;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\ScrapCalendarCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class ScrapCalendarContext extends MotorsportTrackerContext
{
    public const CONTEXT_CALENDAR = Context::CALENDAR->value;

    public const LOCATION_EVENTS = 'event';

    /**
     * @Given the external data source has the calendar for :series :year
     */
    public function theExternalDataSourceHasTheCalendar(PyStringNode $calendar, string $series, int $year): void
    {
        $seasonRef = $this->savedSeasonRef($series, $year);
        $response  = $calendar->getRaw();

        self::container()->cachableConnectorSpy()->prepareResponse(self::CONTEXT_CALENDAR, [$seasonRef], $response);
    }

    /**
     * @Given it has cached the response for calendar for :series :year from the data source
     */
    public function theExternalDataSourceHasTheCachedResponseForCalendar(string $series, int $year, ?TableNode $tableNode = null): void
    {
        $seasonRef = $this->savedSeasonRef($series, $year);
        $response  = null !== $tableNode ? $this->extractResponseFromTable($tableNode) : '';

        self::container()->cachableConnectorSpy()->prepareCache(self::CONTEXT_CALENDAR, [$seasonRef], $response);
    }

    /**
     * @Given the event for :series :year exists
     */
    public function theEventExists(TableNode $tableNode, string $series, int $year): void
    {
        $seasonId = $this->findSeasonId($series, $year);
        $events   = $this->extractDataFromTable($tableNode);

        foreach ($events as $event) {
            self::container()->entityStoreSpy()->add(self::LOCATION_EVENTS, [...$event, 'season' => $seasonId]);
        }
    }

    /**
     * @When I scrap the calendar for :series :year
     */
    public function iScrapTheCalendar(string $series, int $year): void
    {
        self::container()->commandBus()->execute(
            ScrapCalendarCommand::forSeason($series, $year),
        );
    }

    /**
     * @When I scrap the list of calendar for :series :year, asking for the cache to be invalidated
     */
    public function iScrapTheCalendarAskingForTheCacheToBeInvalidated(string $series, int $year): void
    {
        $command = ScrapCalendarCommand::forSeason($series, $year);
        $command->invalidateCache();

        self::container()->commandBus()->execute($command);
    }

    /**
     * @Then the cached response for calendar for :series :year is cleared before requesting the external data source
     */
    public function theCachedResponseForCalendarIsClearedBeforeRequestingTheExternalDataSource(string $series, int $year): void
    {
        $seasonRef = $this->savedSeasonRef($series, $year);

        Assert::assertSame(1, self::container()->cachableConnectorSpy()->timesItInvalidatedCache(
            self::CONTEXT_CALENDAR,
            [$seasonRef],
        ));
    }

    /**
     * @Then the calendar for :series :year from the external source is cached to minimize future calls
     */
    public function theCalendarFromTheExternalSourceIsCachedToMinimizeFutureCalls(string $series, int $year): void
    {
        $seasonRef = $this->savedSeasonRef($series, $year);

        Assert::assertTrue(self::container()->cachableConnectorSpy()->hasCached(self::CONTEXT_CALENDAR, [$seasonRef]));
    }

    /**
     * @Then it saved the event :event for :series :year
     */
    public function itSavedTheEvents(string $event, string $series, int $year): void
    {
        $seasonId = $this->findSeasonId($series, $year);

        $existingId = self::container()->entityStoreSpy()->findIfExists(
            [['season', 'name']],
            self::LOCATION_EVENTS,
            ['season' => $seasonId, 'name' => $event],
        );

        Assert::assertNotNull($existingId);
    }

    private function extractResponseFromTable(TableNode $tableNode): string
    {
        $data = $this->extractDataFromTable($tableNode);

        $response = json_encode($data);
        assert(false !== $response);

        return $response;
    }

    /**
     * @return array<array<string, int|string>>
     */
    private function extractDataFromTable(TableNode $tableNode): array
    {
        $rows = $tableNode->getRows();
        $keys = array_shift($rows);

        return array_map(
            static function (array $row) use ($keys): array {
                return array_combine($keys, $row);
            },
            $rows,
        );
    }

    private function savedSeasonRef(string $series, int $year): string
    {
        $seasonId = $this->findSeasonId($series, $year);

        return (string) self::container()->entityStoreSpy()->stored('season')[$seasonId]['ref'];
    }

    private function findSeasonId(string $series, int $year): string
    {
        $seriesId = self::container()->entityStoreSpy()->findIfExists([['name']], 'series', ['name' => $series]);

        \assert(null !== $seriesId);

        $seasonId = self::container()->entityStoreSpy()->findIfExists(
            [['series', 'year']],
            'season',
            ['series' => $seriesId, 'year' => $year],
        );

        \assert(null !== $seasonId);

        return $seasonId;
    }
}
