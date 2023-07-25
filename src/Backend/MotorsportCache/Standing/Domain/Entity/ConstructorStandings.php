<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\ConstructorStandingsView;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class ConstructorStandings implements CacheItem
{
    private function __construct(
        private ConstructorStandingsView $constructorStandingsView,
    ) {
    }

    public static function create(ConstructorStandingsView $constructorStandingsView): self
    {
        return new self($constructorStandingsView);
    }

    public static function computeKey(string $championship, int $year): string
    {
        return "standings-constructor-{$championship}-{$year}";
    }

    /**
     * @return array{standings: array<int|string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'standings' => $this->constructorStandingsView->value(),
        ];
    }
}
