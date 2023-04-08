<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\EventSessionsDTO;

interface EventRaceSessionsGateway
{
    public function findForEvent(string $eventId): EventSessionsDTO;
}
