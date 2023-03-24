<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewSeasonScheduleQuery implements Query
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
    ) {
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(string $championship, int $year): self
    {
        return new self($championship, $year);
    }
}
