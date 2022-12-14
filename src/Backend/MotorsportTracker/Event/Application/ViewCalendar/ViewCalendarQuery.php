<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewCalendarQuery implements Query
{
    private function __construct(
        private string $month,
        private int $year,
    ) {
    }

    public function month(): string
    {
        return $this->month;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(string $month, int $year): self
    {
        return new self($month, $year);
    }
}
