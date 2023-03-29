<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\DTO\RaceResultDTO;

interface RaceResultGateway
{
    public function findResult(string $eventSessionId): RaceResultDTO;
}
