<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Analytics\AnalyticsServicesTrait as CacheAnalyticsServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\SharedServicesTrait as CacheSharedServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\CalendarServicesTrait as ETLCalendarServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\SeasonServicesTrait as ETLSeasonServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\SeriesServicesTrait as ETLSeriesServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\SharedServicesTrait as ETLSharedServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\MessagingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\PersistenceServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\RandomnessServicesTrait;

final class TestServiceContainer
{
    use CacheSharedServicesTrait;
    use CacheAnalyticsServicesTrait;
    use ETLCalendarServicesTrait;
    use ETLSeasonServicesTrait;
    use ETLSeriesServicesTrait;
    use ETLSharedServicesTrait;
    use MessagingServicesTrait;
    use PersistenceServicesTrait;
    use RandomnessServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
