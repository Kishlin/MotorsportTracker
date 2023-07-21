<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class ComputeSeasonStandingsCommand implements Command
{
    private function __construct(
        private string $championshipName,
        private int $year,
    ) {
    }

    public function championshipName(): string
    {
        return $this->championshipName;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(string $championshipName, int $year): self
    {
        return new self($championshipName, $year);
    }
}
