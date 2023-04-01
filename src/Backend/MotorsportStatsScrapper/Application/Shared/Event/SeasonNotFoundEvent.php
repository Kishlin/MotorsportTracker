<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class SeasonNotFoundEvent implements ApplicationEvent
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

    public static function forSeason(string $championship, int $year): self
    {
        return new self($championship, $year);
    }
}
