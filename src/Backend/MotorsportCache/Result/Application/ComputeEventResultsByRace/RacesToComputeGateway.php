<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

interface RacesToComputeGateway
{
    public function findRaces(string $eventId): RacesToComputeDTO;
}
