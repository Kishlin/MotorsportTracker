<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewDriverStandingsForSeasonResponse implements Response
{
    private function __construct(
        private JsonableStandingsView $driverStandingsView
    ) {
    }

    public function driverStandingsView(): JsonableStandingsView
    {
        return $this->driverStandingsView;
    }

    public static function fromView(JsonableStandingsView $driverStandingsView): self
    {
        return new self($driverStandingsView);
    }
}
