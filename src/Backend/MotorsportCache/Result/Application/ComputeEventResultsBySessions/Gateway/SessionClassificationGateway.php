<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\DTO\SessionResultDTO;

interface SessionClassificationGateway
{
    public function findResult(string $eventSessionId): SessionResultDTO;
}
