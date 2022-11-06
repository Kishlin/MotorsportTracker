<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;

interface TeamStandingsForSeasonGateway
{
    public function view(string $seasonId): JsonableStandingsView;
}
