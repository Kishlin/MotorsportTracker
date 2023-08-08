<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final readonly class CalendarEventScrappingSuccessEvent implements Event
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
