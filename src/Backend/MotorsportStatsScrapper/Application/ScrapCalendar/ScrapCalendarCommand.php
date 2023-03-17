<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class ScrapCalendarCommand implements Command
{
    private function __construct(
        private readonly string $championshipName,
        private readonly int $year,
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
