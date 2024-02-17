<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapCachableResourceCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\EventsFilter;

final class ScrapRaceHistoryCommand extends ScrapCachableResourceCommand
{
    private function __construct(
        private readonly string $seriesName,
        private readonly int $year,
        private readonly ?string $eventFilter,
        bool $cacheMustBeInvalidated,
    ) {
        parent::__construct($cacheMustBeInvalidated);
    }

    public function eventFilter(): EventsFilter
    {
        return EventsFilter::forScalars($this->seriesName, $this->year, $this->eventFilter);
    }

    public static function forEvents(
        string $seriesName,
        int $year,
        ?string $eventFilter,
        bool $cacheMustBeInvalidated = false,
    ): self {
        return new self($seriesName, $year, $eventFilter, $cacheMustBeInvalidated);
    }
}
