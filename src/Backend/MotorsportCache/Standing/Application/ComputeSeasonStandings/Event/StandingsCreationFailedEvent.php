<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Throwable;

final readonly class StandingsCreationFailedEvent implements ApplicationEvent
{
    private function __construct(
        private string $championship,
        private int $year,
        private Throwable $throwable,
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

    public function throwable(): Throwable
    {
        return $this->throwable;
    }

    public static function forSeason(string $championship, int $year, Throwable $throwable): self
    {
        return new self($championship, $year, $throwable);
    }
}
