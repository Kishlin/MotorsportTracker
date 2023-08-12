<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\EventSessionsDTO;

interface EventSessionsGateway
{
    public function findForEvent(string $eventId): EventSessionsDTO;
}
