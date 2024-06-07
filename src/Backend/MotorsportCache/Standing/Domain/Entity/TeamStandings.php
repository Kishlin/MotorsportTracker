<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\TeamStandingsView;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

final readonly class TeamStandings implements CacheItem
{
    private function __construct(
        private TeamStandingsView $teamStandingsView,
    ) {}

    public static function create(TeamStandingsView $teamStandingsView): self
    {
        return new self($teamStandingsView);
    }

    public static function computeKey(string $championship, int $year): string
    {
        return "standings-team-{$championship}-{$year}";
    }

    /**
     * @return array{standings: array<int|string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'standings' => $this->teamStandingsView->value(),
        ];
    }
}
