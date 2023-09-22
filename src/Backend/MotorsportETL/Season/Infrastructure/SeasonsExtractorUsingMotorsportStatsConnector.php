<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Infrastructure;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeasonsExtractor;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeriesDTO;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;

final readonly class SeasonsExtractorUsingMotorsportStatsConnector implements SeasonsExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/series/%s/seasons';

    public function __construct(
        private Connector $connector,
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
