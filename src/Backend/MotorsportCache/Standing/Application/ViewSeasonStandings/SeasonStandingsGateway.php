<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings;

interface SeasonStandingsGateway
{
    public function viewForSeason(string $championshipSlug, int $year): SeasonStandingsJsonableView;
}
