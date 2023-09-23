<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapWithCacheCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonFilter;

final class ScrapStandingsCommand extends ScrapWithCacheCommand
{
    private function __construct(
        private readonly string $seriesName,
        private readonly int $year,
    ) {
    }

    public function seasonFilter(): SeasonFilter
    {
        return SeasonFilter::forScalars($this->seriesName, $this->year);
    }

    public static function forSeason(string $seriesName, int $year): self
    {
        return new self($seriesName, $year);
    }
}
