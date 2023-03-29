<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\DTO\RacesToComputeDTO;

interface RacesToComputeGateway
{
    public function findRaces(string $eventId): RacesToComputeDTO;
}
