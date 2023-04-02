<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewSeasonEventsQuery implements Query
{
    private function __construct(
        private readonly string $championshipSlug,
        private readonly int $year,
    ) {
    }

    public function championshipSlug(): string
    {
        return $this->championshipSlug;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromScalars(string $championshipSlug, int $year): self
    {
        return new self($championshipSlug, $year);
    }
}
