<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportETL\Season;

use Behat\Gherkin\Node\TableNode;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class ScrapSeasonsContext extends MotorsportTrackerContext
{
    public const CONTEXT_SEASONS = Context::SEASONS->value;

    public const LOCATION = 'season';

    /**
     * @Given the external data source has the list of seasons for :series
     */
    public function theExternalDataSourceHasTheListOfSeasons(TableNode $tableNode, string $series): void
    {
        $seriesRef = $this->savedSeriesRef($series);
        $response  = $this->extractResponseFromTable($tableNode);

        self::container()->cachableConnectorSpy()->prepareResponse(self::CONTEXT_SEASONS, [$seriesRef], $response);
    }

    /**
     * @Given it has cached the response for seasons for :series from the data source
     */
    public function theExternalDataSourceHasTheCachedResponseForSeasons(string $series, ?TableNode $tableNode = null): void
    {
        $seriesRef = $this->savedSeriesRef($series);
        $response  = null !== $tableNode ? $this->extractResponseFromTable($tableNode) : '';

        self::container()->cachableConnectorSpy()->prepareCache(self::CONTEXT_SEASONS, [$seriesRef], $response);
    }

    /**
     * @Given the seasons for :series exist
     */
    public function theSeasonsForSeriesExist(TableNode $tableNode, string $series): void
    {
        $seriesId = $this->findSeriesId($series);
        $seasons  = $this->extractDataFromTable($tableNode);

        foreach ($seasons as $season) {
            self::container()->entityStoreSpy()->add(self::LOCATION, [...$season, 'series' => $seriesId]);
        }
    }

    /**
     * @When I scrap the list of seasons for :series
     */
    public function iScrapTheListOfSeasons(string $series): void
    {
        self::container()->commandBus()->execute(
            ScrapSeasonsCommand::forSeries($series),
        );
    }

    /**
     * @When I scrap the list of seasons for :series, asking for the cache to be invalidated
     */
    public function iScrapTheListOfSeasonsAskingForTheCacheToBeInvalidated(string $series): void
    {
        $command = ScrapSeasonsCommand::forSeries($series);
        $command->invalidateCache();

        self::container()->commandBus()->execute($command);
    }

    /**
     * @Then the cached response for seasons for :series is cleared before requesting the external data source
     */
    public function theCachedResponseForSeasonsIsClearedBeforeRequestingTheExternalDataSource(string $series): void
    {
        $seriesRef = $this->savedSeriesRef($series);

        Assert::assertSame(1, self::container()->cachableConnectorSpy()->timesItInvalidatedCache(
            self::CONTEXT_SEASONS,
            [$seriesRef],
        ));
    }

    /**
     * @Then the seasons list for :series from the external source is cached to minimize future calls
     */
    public function theSeasonsListFromTheExternalSourceIsCachedToMinimizeFutureCalls(string $series): void
    {
        $seriesRef = $this->savedSeriesRef($series);

        Assert::assertTrue(self::container()->cachableConnectorSpy()->hasCached(self::CONTEXT_SEASONS, [$seriesRef]));
    }

    /**
     * @Then it saved the seasons for :series :year
     */
    public function itSavedTheSeasons(string $series, int $year): void
    {
        $seriesId = $this->findSeriesId($series);

        $existingId = self::container()->entityStoreSpy()->findIfExists(
            [['year', 'series']],
            self::LOCATION,
            ['year' => $year, 'series' => $seriesId],
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
                $datum = array_combine($keys, $row);

                $datum['year'] = (int) $datum['year'];

                return $datum;
            },
            $rows,
        );
    }

    private function savedSeriesRef(string $series): string
    {
        $seriesId = $this->findSeriesId($series);

        return (string) self::container()->entityStoreSpy()->stored('series')[$seriesId]['ref'];
    }

    private function findSeriesId(string $series): string
    {
        $seriesId = self::container()->entityStoreSpy()->findIfExists([['name']], 'series', ['name' => $series]);
        \assert(null !== $seriesId);

        return $seriesId;
    }
}
