<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final readonly class RaceLapScrappingSuccessEvent implements Event
{
    private function __construct(
        private string $eventId,
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public static function forEvent(string $eventId): self
    {
        return new self($eventId);
    }
}
