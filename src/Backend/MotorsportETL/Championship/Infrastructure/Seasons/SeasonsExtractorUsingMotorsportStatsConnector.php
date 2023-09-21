<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Infrastructure\Seasons;

use Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeasons\SeasonsExtractor;
use Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeasons\SeriesDTO;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Connector\MotorsportStatsConnector;

final readonly class SeasonsExtractorUsingMotorsportStatsConnector implements SeasonsExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/series/%s/seasons';

    public function __construct(
        private MotorsportStatsConnector $connector,
    ) {
    }

    public function extract(SeriesDTO $series): string
    {
        return $this->connector->fetch(
            self::URL,
            [$series->ref()->value()],
        );
    }
}
