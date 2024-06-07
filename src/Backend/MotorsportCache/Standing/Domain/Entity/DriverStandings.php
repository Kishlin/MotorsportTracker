<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\DriverStandingsView;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class DriverStandings implements CacheItem
{
    private function __construct(
        private DriverStandingsView $driverStandingsView,
    ) {}

    public static function create(DriverStandingsView $driverStandingsView): self
    {
        return new self($driverStandingsView);
    }

    public static function computeKey(string $championship, int $year): string
    {
        return "standings-driver-{$championship}-{$year}";
    }

    /**
     * @return array{standings: array<int|string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'standings' => $this->driverStandingsView->value(),
        ];
    }
}
