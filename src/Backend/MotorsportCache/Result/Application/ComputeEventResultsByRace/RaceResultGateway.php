<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace;

interface RaceResultGateway
{
    public function findResult(string $eventSessionId): RaceResultDTO;
}
