<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapWithCacheCommand;

final class ScrapSeasonsCommand extends ScrapWithCacheCommand
{
    private function __construct(
        private readonly string $seriesName,
    ) {
    }

    public function seriesName(): string
    {
        return $this->seriesName;
    }

    public static function forSeries(string $seriesName): self
    {
        return new self($seriesName);
    }
}
