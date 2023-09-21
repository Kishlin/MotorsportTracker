<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeasons;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class ScrapSeasonsCommand implements Command
{
    private function __construct(
        private string $seriesName,
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
