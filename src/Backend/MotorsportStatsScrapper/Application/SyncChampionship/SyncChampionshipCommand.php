<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class SyncChampionshipCommand implements Command
{
    private function __construct(
        private readonly string $seriesSlug,
        private readonly int $year,
    ) {
    }

    public function seriesSlug(): string
    {
        return $this->seriesSlug;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(string $seriesSlug, int $year): self
    {
        return new self($seriesSlug, $year);
    }
}
