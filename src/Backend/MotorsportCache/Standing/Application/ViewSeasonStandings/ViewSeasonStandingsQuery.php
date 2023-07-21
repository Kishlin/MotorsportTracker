<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final readonly class ViewSeasonStandingsQuery implements Query
{
    private function __construct(
        private string $championshipSlug,
        private int $year,
    ) {
    }

    public function championshipSlug(): string
    {
        return $this->championshipSlug;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(string $championshipSlug, int $year): self
    {
        return new self($championshipSlug, $year);
    }
}
