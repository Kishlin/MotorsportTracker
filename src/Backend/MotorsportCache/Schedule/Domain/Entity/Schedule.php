<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Schedule\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Schedule\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class Schedule implements CacheItem
{
    private function __construct(
        private SeasonEventList $events,
    ) {}

    public static function create(SeasonEventList $events): self
    {
        return new self($events);
    }

    public static function computeKey(string $championship, int $year): string
    {
        return "schedule-{$championship}-{$year}";
    }

    public function events(): SeasonEventList
    {
        return $this->events;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->events->value();
    }
}
