<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportCache\Analytics;

use Exception;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\UpdateConstructorAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\UpdateDriverAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\Entity\SeasonAnalytics;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use LogicException;
use PHPUnit\Framework\Assert;
use Psr\Cache\InvalidArgumentException;

final class AnalyticsContext extends MotorsportTrackerContext
{
    private const TYPE_DRIVER = 'driver';

    private const TYPE_CONSTRUCTOR = 'constructor';

    /**
     * @Given the analytics data for :type :name exist
     */
    public function theAnalyticsDataExist(string $type, string $name): void
    {
        try {
            self::container()->coreFixtureLoader()->loadFixture(
                "motorsport.standing.analytics{$type}s.{$this->formatFixtureName($name)}",
            );
        } catch (Exception $e) {
            Assert::fail($e->getMessage());
        }
    }

    /**
     * @When the :type analytics cache is updated for season :series :year
     */
    public function theAnalyticsCacheIsUpdateFor(string $type, string $series, int $year): void
    {
        if (self::TYPE_DRIVER === $type) {
            self::container()->commandBus()->execute(
                UpdateDriverAnalyticsCacheCommand::fromScalars($series, $year),
            );

            return;
        }

        if (self::TYPE_CONSTRUCTOR === $type) {
            self::container()->commandBus()->execute(
                UpdateConstructorAnalyticsCacheCommand::fromScalars($series, $year),
            );

            return;
        }

        throw new LogicException("Unknown analytics type {$type}");
    }

    /**
     * @Then the cache has :type analytics for :series :year
     */
    public function theAnalyticsCacheHasData(string $type, string $series, int $year): void
    {
        $key = $this->formatCacheKey("analytics-{$type}s-{$series}-{$year}");

        try {
            Assert::assertTrue(self::container()->cacheItemPoolSpy()->hasItem($key));
        } catch (InvalidArgumentException $e) {
            Assert::fail($e->getMessage());
        }
    }

    /**
     * @Then the cache for :type analytics for :series :year, at index :index, is for :fixture
     */
    public function theCacheForAnalyticsAtIndexIsFor(string $type, string $series, int $year, int $index, string $fixture): void
    {
        $key = $this->formatCacheKey("analytics-{$type}s-{$series}-{$year}");

        try {
            /** @var SeasonAnalytics $value */
            $value = self::container()->cacheItemPoolSpy()->getItem($key)->get();
        } catch (InvalidArgumentException $e) {
            Assert::fail($e->getMessage());
        }

        /** @var array<string, string> $analyticsView */
        $analyticsView = $value->toArray()[$index];

        $class = self::TYPE_DRIVER === $type ? 'driver' : 'team';

        Assert::assertSame(
            $this->fixtureId("motorsport.{$class}.{$type}.{$this->formatFixtureName($fixture)}"),
            $analyticsView[$type],
        );
    }
}
