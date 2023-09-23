<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportETL\Series;

use Behat\Gherkin\Node\TableNode;
use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\ScrapSeriesListCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;

final class ScrapSeriesListContext extends MotorsportTrackerContext
{
    public const CONTEXT_SERIES = Context::SERIES->value;

    /**
     * @Given /^the external data source has the list of series$/
     */
    public function theExternalDataSourceHasTheListOfSeries(TableNode $tableNode): void
    {
        $response = $this->extractResponseFromTable($tableNode);

        self::container()->cachableConnectorSpy()->prepareNextResponse($response);
    }

    /**
     * @Given /it has cached the response for series from the data source$/
     */
    public function theExternalDataSourceHasTheCachedResponseForSeries(?TableNode $tableNode = null): void
    {
        $response = null !== $tableNode ? $this->extractResponseFromTable($tableNode) : '';

        self::container()->cachableConnectorSpy()->prepareCache(self::CONTEXT_SERIES, [], $response);
    }

    /**
     * @Given the series exists
     */
    public function theSeriesExists(TableNode $table): void
    {
        $rows = $table->getRows();
        $keys = array_shift($rows);

        $data = array_combine($keys, $rows[0]);

        self::container()->entityStoreSpy()->add(self::CONTEXT_SERIES, $data);
    }

    /**
     * @When /^I scrap the list of series$/
     */
    public function iScrapTheListOfSeries(): void
    {
        self::container()->commandBus()->execute(
            ScrapSeriesListCommand::create(),
        );
    }

    /**
     * @When /^I scrap the list of series, asking for the cache to be invalidated$/
     */
    public function iScrapTheListOfSeriesAskingForTheCacheToBeInvalidated(): void
    {
        $command = ScrapSeriesListCommand::create();
        $command->invalidateCache();

        self::container()->commandBus()->execute($command);
    }

    /**
     * @Then the external data source is called :count times
     */
    public function theExternalDataSourceIsCalledXTimes(int $count): void
    {
        Assert::assertSame($count, self::container()->cachableConnectorSpy()->actualRequestsCount());
    }

    /**
     * @Then /^the cached response for series is cleared before requesting the external data source$/
     */
    public function theCachedResponseForSeriesIsClearedBeforeRequestingTheExternalDataSource(): void
    {
        Assert::assertSame(1, self::container()->cachableConnectorSpy()->timesItInvalidatedCache(self::CONTEXT_SERIES));
    }

    /**
     * @Then /^the series list from the external source is cached to minimize future calls$/
     */
    public function theSeriesListFromTheExternalSourceIsCachedToMinimizeFutureCalls(): void
    {
        Assert::assertTrue(self::container()->cachableConnectorSpy()->hasCached(self::CONTEXT_SERIES, []));
    }

    /**
     * @Then it saved :count new series
     */
    public function theTwoSeriesFromTheDataSourceAreSaved(int $count): void
    {
        Assert::assertSame($count, self::container()->entityStoreSpy()->saved(self::CONTEXT_SERIES));
    }

    /**
     * @Then it saved the series :name
     */
    public function itSavedTheSeries(string $name): void
    {
        $existingId = self::container()->entityStoreSpy()->findIfExists([['name']], self::CONTEXT_SERIES, ['name' => $name]);

        Assert::assertNotNull($existingId);
    }

    private function extractResponseFromTable(TableNode $tableNode): string
    {
        $rows = $tableNode->getRows();
        $keys = array_shift($rows);

        $data = [
            array_combine($keys, $rows[0]),
            array_combine($keys, $rows[1]),
        ];

        $response = json_encode($data);
        assert(false !== $response);

        return $response;
    }
}
