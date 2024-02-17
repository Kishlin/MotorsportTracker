<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapCachableResourceCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonFilter;

final class ScrapCalendarCommand extends ScrapCachableResourceCommand
{
    private function __construct(
        private readonly string $seriesName,
        private readonly int $year,
        bool $cacheMustBeInvalidated,
    ) {
        parent::__construct($cacheMustBeInvalidated);
    }

    public function seasonFilter(): SeasonFilter
    {
        return SeasonFilter::forScalars($this->seriesName, $this->year);
    }

    public static function forSeason(string $seriesName, int $year, bool $cacheMustBeInvalidated = false): self
    {
        return new self($seriesName, $year, $cacheMustBeInvalidated);
    }
}
