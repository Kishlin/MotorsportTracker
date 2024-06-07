<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class AvailableStandings implements CacheItem
{
    private function __construct(
        private bool $constructor,
        private bool $team,
        private bool $driver,
    ) {}

    public static function create(bool $constructor, bool $team, bool $driver): self
    {
        return new self($constructor, $team, $driver);
    }

    public static function computeKey(string $championship, int $year): string
    {
        return "standings-available-{$championship}-{$year}";
    }

    public function has(string $type): bool
    {
        $data = $this->toArray();

        return array_key_exists($type, $data) && true === $data[$type];
    }

    /**
     * @return array{constructor: bool, team: bool, driver: bool}
     */
    public function toArray(): array
    {
        return [
            'constructor' => $this->constructor,
            'team'        => $this->team,
            'driver'      => $this->driver,
        ];
    }
}
