<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapCachableResourceCommand;

final class ScrapSeasonsCommand extends ScrapCachableResourceCommand
{
    private function __construct(
        private readonly string $seriesName,
        bool $cacheMustBeInvalidated,
    ) {
        parent::__construct($cacheMustBeInvalidated);
    }

    public function seriesName(): string
    {
        return $this->seriesName;
    }

    public static function forSeries(string $seriesName, bool $cacheMustBeInvalidated = false): self
    {
        return new self($seriesName, $cacheMustBeInvalidated);
    }
}
