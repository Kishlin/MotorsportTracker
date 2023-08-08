<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\DTO\SessionsToComputeDTO;

interface SessionsToComputeGateway
{
    public function findSessions(string $eventId): SessionsToComputeDTO;
}
