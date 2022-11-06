<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewTeamStandingsForSeasonResponse implements Response
{
    private function __construct(
        private JsonableStandingsView $teamStandingsView
    ) {
    }

    public function teamStandingsView(): JsonableStandingsView
    {
        return $this->teamStandingsView;
    }

    public static function fromView(JsonableStandingsView $teamStandingsView): self
    {
        return new self($teamStandingsView);
    }
}
