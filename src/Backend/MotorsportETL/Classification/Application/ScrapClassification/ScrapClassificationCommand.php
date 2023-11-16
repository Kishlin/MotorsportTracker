<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapWithCacheCommand;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\EventsFilter;

final class ScrapClassificationCommand extends ScrapWithCacheCommand
{
    private function __construct(
        private readonly string $seriesName,
        private readonly int $year,
        private readonly ?string $eventFilter,
    ) {
    }

    public function eventFilter(): EventsFilter
    {
        return EventsFilter::forScalars($this->seriesName, $this->year, $this->eventFilter);
    }

    public static function forEvents(string $seriesName, int $year, ?string $eventFilter): self
    {
        return new self($seriesName, $year, $eventFilter);
    }
}
