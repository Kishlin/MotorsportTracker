<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph;

interface FastestLapDeltaDataGateway
{
    public function findForSession(string $session): FastestLapDeltaData;
}
