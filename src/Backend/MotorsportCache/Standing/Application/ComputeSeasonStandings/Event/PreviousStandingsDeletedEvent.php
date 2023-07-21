<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class PreviousStandingsDeletedEvent implements ApplicationEvent
{
    private function __construct(
        private string $championship,
        private int $year,
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

    public static function forSeason(string $championship, int $year): self
    {
        return new self($championship, $year);
    }
}
