<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\CalendarServicesTrait as ETLCalendarServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\SeasonServicesTrait as ETLSeasonServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\SeriesServicesTrait as ETLSeriesServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL\SharedServicesTrait as ETLSharedServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\MessagingServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\RandomnessServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\Services\Shared\TimeServicesTrait;

final class TestServiceContainer
{
    use ETLCalendarServicesTrait;
    use ETLSeasonServicesTrait;
    use ETLSeriesServicesTrait;
    use ETLSharedServicesTrait;
    use MessagingServicesTrait;
    use RandomnessServicesTrait;
    use TimeServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
