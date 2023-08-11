<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\LapByLapData;

interface LapByLapDataGateway
{
    public function findForSession(string $session, float $maxTimeRatio): LapByLapData;
}
