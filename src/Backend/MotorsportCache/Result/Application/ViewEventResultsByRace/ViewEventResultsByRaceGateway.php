<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace;

interface ViewEventResultsByRaceGateway
{
    public function viewForEvent(string $event): EventResultsByRaceJsonableView;
}
