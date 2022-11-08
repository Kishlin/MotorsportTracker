<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchCarQuery implements Query
{
    private function __construct(
        private int $number,
        private string $team,
        private string $championship,
        private int $year,
    ) {
    }

    public function number(): int
    {
        return $this->number;
    }

    public function team(): string
    {
        return $this->team;
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(int $number, string $team, string $championship, int $year): self
    {
        return new self($number, $team, $championship, $year);
    }
}
