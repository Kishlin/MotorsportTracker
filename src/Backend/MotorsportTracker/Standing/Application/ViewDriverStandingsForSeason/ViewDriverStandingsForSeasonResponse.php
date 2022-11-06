<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableDriverStandingsView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewDriverStandingsForSeasonResponse implements Response
{
    private function __construct(
        private JsonableDriverStandingsView $driverStandingsView
    ) {
    }

    public function driverStandingsView(): JsonableDriverStandingsView
    {
        return $this->driverStandingsView;
    }

    public static function fromView(JsonableDriverStandingsView $driverStandingsView): self
    {
        return new self($driverStandingsView);
    }
}
