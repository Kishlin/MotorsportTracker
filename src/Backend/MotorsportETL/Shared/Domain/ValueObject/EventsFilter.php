<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject;

final readonly class EventsFilter
{
    private function __construct(
        private string $seriesName,
        private int $year,
        private ?string $eventFilter,
    ) {
    }

    public function seriesName(): string
    {
        return $this->seriesName;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function eventFilter(): ?string
    {
        return $this->eventFilter;
    }

    public static function forScalars(string $seriesName, int $year, ?string $eventFilter): self
    {
        return new self($seriesName, $year, $eventFilter);
    }
}
