<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewTeamStandingsForSeasonQuery implements Query
{
    private function __construct(
        private string $seasonId,
    ) {
    }

    public function seasonId(): string
    {
        return $this->seasonId;
    }

    public static function fromScalars(string $seasonId): self
    {
        return new self($seasonId);
    }
}
