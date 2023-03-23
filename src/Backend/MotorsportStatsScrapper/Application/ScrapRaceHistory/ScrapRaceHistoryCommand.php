<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class ScrapRaceHistoryCommand implements Command
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
        private readonly string $event,
        private readonly string $sessionType,
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

    public function event(): string
    {
        return $this->event;
    }

    public function sessionType(): string
    {
        return $this->sessionType;
    }

    public static function fromScalars(string $championship, int $year, string $event, string $sessionType): self
    {
        return new self($championship, $year, $event, $sessionType);
    }
}
