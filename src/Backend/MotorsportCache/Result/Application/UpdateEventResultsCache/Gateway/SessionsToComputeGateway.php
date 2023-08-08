<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\DTO\SessionsToComputeDTO;

interface SessionsToComputeGateway
{
    public function findSessions(string $eventId): SessionsToComputeDTO;
}
