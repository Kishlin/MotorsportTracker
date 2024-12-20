<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\UpdateConstructorAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\UpdateDriverAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\ScrapCalendarCommand;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsCommand;
use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\ScrapSeriesListCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final readonly class TestCommandBus implements CommandBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer,
    ) {}

    public function execute(Command $command): mixed
    {
        // Cache

        if ($command instanceof UpdateDriverAnalyticsCacheCommand) {
            // @phpstan-ignore-next-line
            return $this->testServiceContainer->updateDriverDriverAnalyticsCacheCommandHandler()($command);
        }

        if ($command instanceof UpdateConstructorAnalyticsCacheCommand) {
            // @phpstan-ignore-next-line
            return $this->testServiceContainer->updateConstructorAnalyticsCacheCommandHandler()($command);
        }

        // ETL

        if ($command instanceof ScrapSeriesListCommand) {
            return $this->testServiceContainer->scrapSeriesListCommandHandler()($command);
        }

        if ($command instanceof ScrapSeasonsCommand) {
            return $this->testServiceContainer->scrapSeasonsCommandHandler()($command);
        }

        if ($command instanceof ScrapCalendarCommand) {
            return $this->testServiceContainer->scrapCalendarCommandHandler()($command);
        }

        throw new RuntimeException('Unknown command type: ' . get_class($command));
    }
}
