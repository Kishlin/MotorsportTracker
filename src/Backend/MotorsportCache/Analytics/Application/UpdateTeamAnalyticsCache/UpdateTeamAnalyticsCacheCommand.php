<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class UpdateTeamAnalyticsCacheCommand implements Command
{
    private function __construct(
        private string $championship,
        private int $year,
    ) {}

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
