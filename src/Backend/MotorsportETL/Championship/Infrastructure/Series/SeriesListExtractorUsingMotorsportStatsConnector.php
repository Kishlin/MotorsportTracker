<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Infrastructure\Series;

use Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList\SeriesListExtractor;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Connector\MotorsportStatsConnector;

final readonly class SeriesListExtractorUsingMotorsportStatsConnector implements SeriesListExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/series';

    public function __construct(
        private MotorsportStatsConnector $connector,
    ) {
    }

    public function extract(): string
    {
        return $this->connector->fetch(self::URL);
    }
}
