<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;

interface DriverStandingsForSeasonGateway
{
    public function view(string $seasonId): JsonableStandingsView;
}