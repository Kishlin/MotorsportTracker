<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Infrastructure;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\SeriesListExtractor;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;

final readonly class SeriesListExtractorUsingMotorsportStatsConnector implements SeriesListExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/series';

    public function __construct(
        private Connector $connector,
    ) {}

    public function extract(): string
    {
        return $this->connector->fetch(self::URL);
    }
}
