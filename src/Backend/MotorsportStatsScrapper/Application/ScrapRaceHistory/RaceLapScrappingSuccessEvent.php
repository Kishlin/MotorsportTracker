<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final readonly class RaceLapScrappingSuccessEvent implements Event
{
    private function __construct(
        private string $championship,
        private string $eventId,
    ) {
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public static function forEvent(string $championship, string $eventId): self
    {
        return new self($championship, $eventId);
    }
}
