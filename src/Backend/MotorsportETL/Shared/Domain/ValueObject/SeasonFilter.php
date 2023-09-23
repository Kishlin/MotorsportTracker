<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject;

final readonly class SeasonFilter
{
    private function __construct(
        private string $seriesName,
        private int $year,
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

    public static function forScalars(string $seriesName, int $year): self
    {
        return new self($seriesName, $year);
    }
}
